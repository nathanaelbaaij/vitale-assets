//defines
var OSMLayer = new ol.layer.Tile({source: new ol.source.OSM()});
var overstromingsLayer = new ol.layer.Tile();
var breachlocationsLayer;
var categoryLayers = [];

var currentLoadLevel;

var map;

//URLS
var geoServerURL = 'http://185.66.250.247:8080/geoserver/wms';
var APIServerURL = 'http://185.66.250.247/api/calculateScenario';

var popup = document.getElementById('popup-container');

// to make sure we do not request something after each click
var RequestLayersTimer;

//Openlayers needs thses definitions to tranfrom it to rasterdata
proj4.defs('EPSG:28992', '+proj=sterea +lat_0=52.15616055555555 +lon_0=5.38763888888889 ' +
'+k=0.9999079 +x_0=155000 +y_0=463000 +ellps=bessel ' +
'+towgs84=565.4171,50.3319,465.5524,-0.398957,0.343988,-1.87740,4.0725 +units=m +no_defs');
var imageExtent = [59100, 378900, 74900, 392900];

/**
* When the user clicks on a icon in the map show a popup container
* @type {ol.Overlay}
*/
var overlay = new ol.Overlay({
    element: popup,
    positioning: 'bottom-center',
    offset: [0, -20],
});

/*
* jQuery OnLoad function
*/
$(document).ready(function () {
    
    //init map
    map = new ol.Map({
        target: document.getElementById('map'),
        layers: [
            OSMLayer, // First layer is the OSMLayer
            overstromingsLayer, // Second layer is the overstromings layer
        ],
        view: new ol.View({
            // hier transformeert Proj4 de rasterdata
            center: ol.proj.transform(ol.extent.getCenter(imageExtent), 'EPSG:28992', 'EPSG:3857'),
            zoom: 12,
        }),
        //removes double click zoom
        interactions: ol.interaction.defaults({doubleClickZoom: false}),
    });
    
    map.addOverlay(overlay);
    
    //user clicks on map
    map.on('click', userClicksOnMap);
    
    //change mouse cursor
    map.on('pointermove', userPointerMove);
    
    //TODO: Maak een generieke functie LoadUserScenario() voor alle scenario gerelateerde scenario functies
    
    //set loadlevel from the database
    getScenarioLoadLevel();
    
    /**
    * Load the breachlocation layer and the current breachlocation user scenario
    * then select the breachlocations on the map
    */
    $.when(addBreachLocationLayer(), getScenarioBreachLocation()).done(function (layer, ids) {
        initBreachLocationScenario(ids[0]);
    });
    
    /**
    * init categories scenario
    */
    $.when(addCategoryLayers(), getScenarioCategories()).done(function (layer, categoryIds) {
        initCategoryScenario(categoryIds[0]);
    });
    
    switchScenarioLoadLevel();
    
    clearBreachLocationTrigger();
    clearCategoriesTrigger();
});

/**
*
* @param categoryIds
*/
function initCategoryScenario(categoryIds) {
    
    //check the checkboxes
    $('.category-checkbox').each(function (i, obj) {
        if (categoryIds.includes(parseInt(obj.name))) {
            $(obj).prop("checked", true);
        }
    });
    
    //load asset category api
    $(categoryIds).each(function (i, categoryId) {
        
        var layer = findCategoryLayer(categoryId);
        
        //check if there is a layer source, if not get the new data
        if (layer.getSource() == null) {
            //add spinner class to icon element
            $('#cat-' + categoryId).addClass("fa-spin");
            //ajax call the new data
            $.ajax({
                layer: layer,
                id: categoryId,
                url: '../api/assets?cat=' + categoryId,
                type: 'get',
                success: function (data) {
                    layer = this.layer;
                    layer.setSource(
                        new ol.source.Vector({
                            features: (new ol.format.GeoJSON()).readFeatures(data)
                        })
                    );
                    //remove spinner class to icon element
                    $('#cat-' + this.id).removeClass("fa-spin");
                }
            });
        }
    });
    
}

/**
* Make the breachlocation icons pink if selected and load the overstromingslayer
*/
function initBreachLocationScenario(data) {
    
    //get all features with a selected state
    var requestLayerParams = {
        "scenarios": [],
        "ids": [],
    };
    
    //loop through all the breachlocations
    breachlocationsLayer.getSource().forEachFeature(function (feature) {
        
        //if the breachlocation scenario is the same of the breachlocation on the map
        if (data.includes(feature.values_.id)) {
            //set the state of the breachlocation selected
            feature.values_.state = 'selected';
            
            //create the layer name for the geoserver
            var layerName = "WD-" + feature.values_.id + "-" + currentLoadLevel;
            
            //prepare request for server
            requestLayerParams.scenarios.push(layerName);
            requestLayerParams.ids.push(feature.values_.id);
            
        } else {
            //breachlocation not selected
            feature.values_.state = null;
        }
    });
    
    //update layer
    breachlocationsLayer.changed();
    
    //Check if there is 1, 1 or more or none breachlocation selected
    if (requestLayerParams.scenarios.length == 1) {
        
        //prepare layername from current requested scenario
        var layerName = "Dijkring_31:" + requestLayerParams.scenarios[0];
        
        changeOverstromingsSource(geoServerURL, layerName);
        
    } else if (requestLayerParams.scenarios.length > 1) {
        
        requestMaxLayer({
            scenarios: requestLayerParams.scenarios,
            "resolution": 10
        });
        
    } else {
        //when the user doesnt select an breachlocation at all then the overstromingslayer isnt visible
        overstromingsLayer.setVisible(false);
    }
}

/**
* When the user clicks on a asset or breachlocation
* @returns {boolean}
*/
function userClicksOnMap(evt) {
    
    //reset the overlay position
    overlay.setPosition();
    
    //get the feature
    var feature = map.getFeaturesAtPixel(evt.pixel);
    
    if (feature) {
        feature = feature[0];
    } else {
        console.warn("No feature selected: " + evt.pixel);
        return false;
    }
    
    //set coordinate
    var coordinate = evt.coordinate;
    
    //check if the feature is a breachlocation
    if (feature.get('Asset') === "Breachlocation") {

        showBreachLocationPopup(evt);
        
        overstromingsLayer.setVisible(false);
        
        //set breachlocation feature as selected or null if already selected
        if (feature.values_.state && feature.values_.state == 'selected') {
            feature.values_.state = null;
        } else {
            feature.values_.state = 'selected';
        }
        
        //get all features with a selected state
        var requestLayerParams = {
            "scenarios": [],
            "ids": [],
        };
        
        //loop through all the breachlocations
        breachlocationsLayer.getSource().forEachFeature(function (feature) {
            
            //retrieve only the selected breachlocations
            if (feature.values_.state == 'selected') {
                
                //create the layer name for the geoserver
                var layerName = "WD-" + feature.values_.id + "-" + currentLoadLevel;
                
                //prepare request for server
                requestLayerParams.scenarios.push(layerName);
                requestLayerParams.ids.push(feature.values_.id);
            }
        });
        
        //toggle breachlocations in db
        toggleScenarioBreachLocations(requestLayerParams.ids);
        
        //clean request for the new one
        clearTimeout(RequestLayersTimer);
        
        //Check if there is 1, 1 or more or none breachlocation selected
        if (requestLayerParams.scenarios.length == 1) {
            
            //prepare layername from current requested scenario
            var layerName = "Dijkring_31:" + requestLayerParams.scenarios[0];
            
            //wait 2 seconds till requesting a new scenario
            RequestLayersTimer = setTimeout(changeOverstromingsSource, 2000, geoServerURL, layerName);
            
            
            
        } else if (requestLayerParams.scenarios.length > 1) {
            
            //one or more breachlocations are selected
            RequestLayersTimer = setTimeout(requestMaxLayer, 2000, {
                scenarios: requestLayerParams.scenarios,
                "resolution": 10
            });
            
            
        } else {
            //when the user doesnt select an breachlocation at all then the overstromingslayer isnt visible
            overstromingsLayer.setVisible(false);
        }
        
        //update assetslayer
        refreshAssetCategoryLayers();
        
        //update color breachlocation icons
        breachlocationsLayer.changed();
        
    } else {
        
        
        //fill popup with content
        $(overlay.getElement()).html(getPopupContent(feature, false));
        
        //set the overlay coordinates
        overlay.setPosition(coordinate);
        
        //for fadeout effect
        overlay.getElement().style.display = "block";
        
        //the overlay fades out after some time
        setTimeout(function () {
            var fadeTarget = overlay.getElement();
            var fadeEffect = setInterval(function () {
                if (!fadeTarget.style.opacity) {
                    fadeTarget.style.opacity = 1;
                }
                if (fadeTarget.style.opacity > 0) {
                    fadeTarget.style.opacity -= 0.1;
                } else {
                    fadeTarget.style.display = "none";
                    fadeTarget.style.opacity = 1;
                    clearInterval(fadeEffect);
                }
            }, 50);
        }, 3000);
        
        
    }
}

/**
 * 
 */
function showBreachLocationPopup(evt) {
    
    //reset the overlay position
    overlay.setPosition();
    
    //get the feature
    var feature = map.getFeaturesAtPixel(evt.pixel);
    
    if (feature) {
        feature = feature[0];
    } else {
        console.warn("No feature selected: " + evt.pixel);
        return false;
    }
    
    //check if the feature is a breachlocation
    if (feature.get('Asset') === "Breachlocation") {    
        
        //set coordinate
        var coordinate = evt.coordinate;
        
        //fill popup with content
        $(overlay.getElement()).html(getPopupContent(feature, feature && feature.get('Asset') === "Breachlocation"));
        
        //set the overlay coordinates
        overlay.setPosition(coordinate);
        
        //for fadeout effect
        overlay.getElement().style.display = "block";
        
        //the overlay fades out after some time
        setTimeout(function () {
            var fadeTarget = overlay.getElement();
            var fadeEffect = setInterval(function () {
                if (!fadeTarget.style.opacity) {
                    fadeTarget.style.opacity = 1;
                }
                if (fadeTarget.style.opacity > 0) {
                    fadeTarget.style.opacity -= 0.1;
                } else {
                    fadeTarget.style.display = "none";
                    fadeTarget.style.opacity = 1;
                    clearInterval(fadeEffect);
                }
            }, 50);
        }, 3000);
        
    }
    
}

/**
* when the user moves with the pointer over the map
* it changes the cursor on hover over an icon like
* asset or breachlocation
* @param e
*/
function userPointerMove(e) {
    if (e.dragging) {
        $(popup).popover('destroy');
        return;
    }
    var pixel = map.getEventPixel(e.originalEvent);
    var hit = map.hasFeatureAtPixel(pixel);
    
    map.getTarget().style.cursor = hit ? 'pointer' : '';    
}

/**
* this method returns the content for the feature
* @param feature
* @param ifBreachLocation checks if the feature is a breachlocation
* @returns {*}
*/
function getPopupContent(feature, ifBreachLocation) {
    
    var popUpContent;
    
    if (ifBreachLocation === true) {
        popUpContent = `
        <table class="table">
        <tr>
        <th>Naam</th>            
        <td>${feature.get('name')}</td>            
        </tr>
        <tr>
        <th>Dijkring</th>            
        <td>${feature.get('dykering')}</td>            
        </tr>
        <tr>
        <th>vnk2</th>            
        <td>${feature.get('vnk2')}</td>            
        </tr>
        </table>`;
        
        return popUpContent;
    }
    
    if (ifBreachLocation === false) {
        popUpContent = `
        
        <table class="table">
        ${feature.get('name') !== null ? `<tr><th>Naam</th><td>${feature.get('name')}</td></tr>` : ''}
        ${feature.get('description') !== null ? `<tr><th>Beschrijving</th><td>${feature.get('description')}</td></tr>` : ''}
        ${feature.get('category').name !== null ? `<tr><th>Categorie</th><td>${feature.get('category').name}</td></tr>` : ''}
        ${feature.get('current_water_depth') !== null ? `<tr><th>Waterdiepte</th><td>${feature.get('current_water_depth')}</td></tr>` : ''}
        ${feature.get('category').threshold !== null ? `<tr><th>Drempelwaarde</th><td>${feature.get('category').threshold}</td></tr>` : ''}
        ${feature.get('threshold_correction').threshold !== null ? `<tr><th>Drempelwaarde correctie</th><td>${feature.get('threshold_correction')}</td></tr>` : ''}             
        ${feature.get('image') !== null ? '<tr><th>Foto</th><td><img style="width: 150px; display: block;" src="' + feature.get('image') + '"></td></tr>' : ''}
        </table>
        
        ${feature.get('links').self !== undefined ? `<a class="btn btn-primary btn-sm" href="${feature.get('links').self}" role="button">Meer informatie</a>` : ''}
        ${feature.get('links').edit !== undefined ? `<a class="btn btn-default btn-sm" href="${feature.get('links').edit}" role="button">Wijzigen</a>` : ''}
        
        `;
    }
    
    return popUpContent;
}


/**
* get categories id scenario
* @returns {*}
*/
function getScenarioCategories() {
    return $.ajax({
        url: '/scenario/category/get',
        type: 'get',
    });
}

/**
*
*/
function getScenarioLoadLevel() {
    $.ajax({
        url: '/scenario/loadlevel/get',
        type: 'get',
        success: function (id) {
            
            console.log('current scenariolevel is ' + id);
            
            currentLoadLevel = id;
            $('#ll-' + id).addClass('active').siblings().removeClass('active');
        }
    });
}

/**
* set or remove category from user scenario
* @param id
* @param state
*/
function toggleScenarioCategories(id, state) {
    return $.ajax({
        url: '/scenario/category/toggle',
        type: 'get',
        data: {'id': id, 'state': state},
    });
}

/**
* removes checkboxes from the filter menu
* clears category layer
* clears scenario database
*/
function clearCategoriesTrigger() {
    $('.cat-reset').click(function () {
        
        //remove checkboxes
        $('.category-checkbox').each(function (i, obj) {
            $(obj).prop('checked', false);
        });
        
        //remove source from categoryLayers
        for (var i = 0; i < categoryLayers.length; i++) {
            
            //set category layer
            var catLayer = categoryLayers[i].layer;
            
            //check if there is a source already
            if (catLayer.getSource() != null) {
                catLayer.setSource(null);
            }
        }
        
        //update layer
        catLayer.changed();
        
        //clear db
        clearScenarioCategories();
    });
}

/**
* Checks if the user clicks on the clear button on the map
* clears all selected breachlocation front and back -end
* updates the map
*/
function clearBreachLocationTrigger() {
    $('.bl-reset').click(function () {
        
        breachlocationsLayer.getSource().forEachFeature(function (feature) {
            if (feature.values_.state && feature.values_.state == 'selected') {
                feature.values_.state = null;
            }
        });
        
        breachlocationsLayer.changed();
        
        clearScenarioBreachLocation();
    });
}

/**
* adds or removes .active class from the loadlevel button group
*/
function switchScenarioLoadLevel() {
    $('#loadlevel .btn-group .btn').click(function () {
        
        var loadlevelBtn = this;
        
        $.ajax({
            url: '/scenario/loadlevel/switch',
            type: 'get',
            data: {id: $(this).data('loadlevel')},
            success: function () {
                $(loadlevelBtn).addClass('active').siblings().removeClass('active');
                currentLoadLevel = $(loadlevelBtn).data('loadlevel');
                
                //Refresh the category asset layers
                refreshAssetCategoryLayers();
                
                //For better user experience hide the overstormingslayer
                overstromingsLayer.setVisible(false);
                
                //get the new 'overstromingssource'
                $.when(addBreachLocationLayer(), getScenarioBreachLocation()).done(function (layer, ids) {
                    initBreachLocationScenario(ids[0]);
                    //show the overstormingslayer
                    overstromingsLayer.setVisible(true);
                });
            }
        });
        
    });
}

/**
* get all breachlocations from the user session
*/
function getScenarioBreachLocation() {
    return $.ajax({
        url: "/scenario/breach/get",
        type: 'get',
    });
}

/**
*
* @param BreachlocationsArray
* @returns {*}
*/
function toggleScenarioBreachLocations(BreachlocationsArray) {
    $.ajax({
        url: '/scenario/breach/toggle',
        type: 'get',
        data: {'ids': BreachlocationsArray},
    });
}

function clearScenarioBreachLocation() {
    overstromingsLayer.setVisible(false);
    $.ajax({
        url: '/scenario/breach/clear',
        type: 'get',
    });
}

/**
* TODO: uncheck checkboxes in filter menu
* TODO: categoryLayers onzichtbaar met setVisible false
*/
function clearScenarioCategories() {
    $.ajax({
        url: '/scenario/category/clear',
        type: 'get',
    });
}


/**
* Set a style for all breachlocation icons
* @type {ol.style.Style}
*/
var breachLocationStyle = new ol.style.Style({
    text: new ol.style.Text({
        text: "\uf041",
        font: "normal 32px FontAwesome",
        textBaseline: 'bottom',
        fill: new ol.style.Fill({
            color: '#111111',
        }),
        stroke: new ol.style.Stroke({
            color: 'black',
            width: 1,
        }),
    }),
});

/**
* Set a default style for all assets icons
* @type {ol.style.Style}
*/
var assetStyles = {
    'Point': new ol.style.Style({
        text: new ol.style.Text({
            text: "\ue906", //f1eb f2ce
            font: "normal 18px vitaleassets",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: '#B4B0AA',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'LineString': [
        new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'white',
                width: 4,
            }),
            zIndex: 2
        }),
        new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 6,
            }),
            zIndex: 1
        }),
    ],
    'MultiLineString': [
        new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'white',
                width: 4,
            }),
            zIndex: 2
        }),
        new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 6,
            }),
            zIndex: 1
        }),
    ]
};


/**
* Set the selected breachlocation to a highlighted different color otherwise
* set the default gray color.
* @param feature
* @returns {ol.style.Style}
*/
var breachLocationStyleFunction = function (feature) {
    
    //get the breachlocation properties
    var properties = feature.getProperties();
    
    //check if the breachlocation is selected
    if (properties.state == 'selected') {
        color = '#D81B60';
    } else {
        color = '#4b4c54';
    }
    breachLocationStyle.getText().getFill().setColor(color);
    
    return breachLocationStyle;
};

/**
* Set the right color and symbol for each asset icon on the map
* @param feature
* @returns {ol.style.Style}
*/
var assetStyleFunction = function (feature) {
    
    var assetType = feature.getGeometry().getType();
    var symbol = feature.getProperties().symbol;
    var stateColor = feature.getProperties().state_color;
    var assetStyle = assetStyles[assetType];
    
    //check if the assettype is a point or a linestring
    switch (assetType) {
        case 'Point' :
        assetStyle.getText().setText(String.fromCodePoint(parseInt(symbol.toString(), 16)));
        assetStyle.getText().getFill().setColor(stateColor);
        break;
        case 'LineString' :
        assetStyle[0].getStroke().setColor(stateColor);
        break;
    }
    
    return assetStyle;
};

/**
* This function does the following:
* - gets current breachlocation id
* - prepares the right layer name for the GEOServer
* - requests the layer from the GEOServer
* - sets the new overstromings source in the map
* - Loop through each already loaded asset layers, and reload the new source
*/
function changeOverstromingsSource(url, layer) {
    
    //Set overstromings source from the requested breachlocation id
    var overstromingsSource = new ol.source.TileWMS({
        url: url,
        params: {
            'FORMAT': 'image/png',
            'VERSION': '1.1.1',
            tiled: true,
            STYLES: '',
            LAYERS: layer,
        }
    });
    
    //change the source from the overstromingslayer
    overstromingsLayer.setSource(overstromingsSource);
    
    //set  overstromings layer visible
    overstromingsLayer.setVisible(true);
}

/**
*
*/
function refreshAssetCategoryLayers() {
    //loop through each already loaded asset layers, and reload the new source
    for (var i = 0; i < categoryLayers.length; i++) {
        
        var catName = categoryLayers[i].name;
        var catLayer = categoryLayers[i].layer;
        
        //check if there is a source already
        if (catLayer.getSource() != null) {
            
            //loop through all asset category layer and set the color back to loading color gray
            var features = catLayer.getSource().getFeatures();
            for (var j = 0; j < features.length; j++) {
                features[j].set('state_color', "#B4B0AA");
            }
            //update the layer
            catLayer.changed();
            
            //get the new information and set it
            $.ajax({
                layer: catLayer,
                name: this.name,
                url: '../api/assets?cat=' + catName,
                type: 'get',
                success: function (data) {
                    catLayer = this.layer;
                    catLayer.setSource(
                        new ol.source.Vector({
                            features: (new ol.format.GeoJSON()).readFeatures(data)
                        })
                    );
                }
            });
        }
    }
}

/**
* Add all breachlocations to the map
*/
function addBreachLocationLayer() {
    return $.ajax({
        url: '../api/breachlocations',
        type: 'get',
        success: function (data) {
            breachlocationsLayer = new ol.layer.Vector({
                source: new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(data)
                }),
                style: breachLocationStyleFunction //add the right style
            });
            map.addLayer(breachlocationsLayer);
        }
    });
}

/**
* Find category layer by given name
* @param id is category name
* @returns {*}
*/
function findCategoryLayer(id) {
    for (var i = 0; i < categoryLayers.length; i++) {
        if (categoryLayers[i].name === id)
        return categoryLayers[i].layer;
    }
    return null;
}

/**
* loops through all the category checkboxes in the view and gives
* the domelement to the addCategoryLayer method
*/
function addCategoryLayers() {
    $('.category-checkbox').each(function (i, obj) {
        // assign updateTotal function to onclick property of each checkbox
        addCategoryLayer(obj);
    });
}

/**
* Load in the category layers
* Bind the layer with the filter menu
* @param obj
*/
function addCategoryLayer(obj) {
    //define category id
    var name = parseInt(obj.name);
    
    //define the new category layer without the source yet
    var newLayer = new ol.layer.Vector({
        source: null,
        style: assetStyleFunction //adds the right style to the layer
    });
    
    //when the user clicks on a checkbox
    obj.onclick = function () {
        
        var catCheckbox = $(".category-checkbox");
        
        //disable all checkboxes in filter list
        catCheckbox.prop("disabled", true);
        catCheckbox.parent().addClass("checkbox-disabled");
        
        //find the category layer
        var layer = findCategoryLayer(parseInt(this.name));
        //if the layer is checked get the new data
        if (this.checked) {
            //check if there is a layer source, if not get the new data
            if (layer.getSource() == null) {
                //add spinner class to icon element
                $('#cat-' + this.name).addClass("fa-spin");
                //ajax call the new data
                $.ajax({
                    layer: layer,
                    id: this.name,
                    url: '../api/assets?cat=' + this.name,
                    type: 'get',
                    success: function (data) {
                        layer = this.layer;
                        layer.setSource(
                            new ol.source.Vector({
                                features: (new ol.format.GeoJSON()).readFeatures(data)
                            })
                        );
                        //remove spinner class to icon element
                        $('#cat-' + this.id).removeClass("fa-spin");
                    }
                });
            }
        }
        
        //if the data is already there, change the visibility
        layer.setVisible(this.checked);
        
        //update category list in user scenario
        toggleScenarioCategories(obj.name, this.checked).then(function (response) {
            //enables all checkboxes in filter list
            catCheckbox.prop("disabled", false);
            catCheckbox.parent().removeClass("checkbox-disabled");
        });
    };
    
    //add layer to map
    map.addLayer(newLayer);
    
    //add layer to categoryLayers array
    categoryLayers.push({
        name: name,
        layer: newLayer
    });
}

/**
*
* @param RequestParams
* @constructor
*/
function requestMaxLayer(RequestParams) {
    httpPOSTAsync(APIServerURL, RequestParams, function (data) {
        changeOverstromingsSource(data.result.wms_url, data.result.layername);
    });
}

/**
* Javascript AJAX method
* @param URL
* @param jsonData
* @param callback
*/
function httpPOSTAsync(URL, jsonData, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', URL);
    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    xhr.onload = function () {
        if (xhr.readyState == 4 && xhr.status === 200) {
            console.log(xhr.responseText);
            var jsonResults = JSON.parse(xhr.responseText);
            if (jsonResults.result.err) {
                console.warn(result.err);
            }
            else {
                callback(JSON.parse(xhr.responseText));
            }
        }
    };
    
    xhr.send(JSON.stringify(jsonData));
}

/**
* When you want to remove an layer
* searching by name
* @param layerName
*/
function removeLayerByName(layerName) {
    map.getLayers().forEach(function (layer) {
        if (layer != undefined && layer.values_.name == layerName) {
            map.removeLayer(layer);
            return;
        }
    });
}
