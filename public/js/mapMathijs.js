//cookie functionaliteiten
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}


/*
 * jQuery OnLoad functie. Laadt assetbestand in en voegt dit toe als een nieuwe laag.
 */
$(function () {
    addVectorLayer("breach_locations.json", "breachLocations", 100);
    addVectorLayer("assets_locations.json", "assetLocations", 50);
});

/*
 * Deze definities heeft openlayers (en proj4) nodig om de rasterdata te transformeren
 */
proj4.defs('EPSG:28992', '+proj=sterea +lat_0=52.15616055555555 +lon_0=5.38763888888889 ' +
    '+k=0.9999079 +x_0=155000 +y_0=463000 +ellps=bessel ' +
    '+towgs84=565.4171,50.3319,465.5524,-0.398957,0.343988,-1.87740,4.0725 +units=m +no_defs');
var imageExtent = [59100, 378900, 74900, 392900];


var addVectorLayer = function (name, layername, zIndex) {
    $.getJSON(name, function (data) {
        // data bevat de geojson data in Web Mercator ESPG:3857 (https://epsg.io/3857)
        var vectorSource = new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(data)
        });
        // Nieuwe vectorlaag aan de map toevoegen
        map.addLayer(
            new ol.layer.Vector({
                name: layername,
                source: vectorSource,
                style: styleFunction, // zorgt ervor dat per asset een passende stijl wordt gekozen
                zIndex: zIndex
            }));
    });
};

/*
 * De stijlen waarmee de verschillende assets worden gerenderd. Ik maak nu gebruik van FontAwesome
 */
var styles = {
    'Breachlocation': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf041",
            font: "normal normal normal 32px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: '#111111',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'C2000': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf1eb",
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'Gemaal': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf0a3",
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'Rioolgemaal': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf06b",
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'Straatkast': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf108",
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'Zendmast': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf2ce", //f1eb f2ce
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'GSM 1800': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf2ce", //f1eb f2ce
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'GSM 900': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf2ce", //f1eb f2ce
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'LTE': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf2ce", //f1eb f2ce
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'UMTS': new ol.style.Style({
        text: new ol.style.Text({
            text: "\uf2ce", //f1eb f2ce
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
    'Wijkcentrale': new ol.style.Style({
        text: new ol.style.Text({
            text: "&#xf108", //f1eb f2ce
            font: "normal normal normal 18px FontAwesome",
            textBaseline: 'bottom',
            fill: new ol.style.Fill({
                color: 'black',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 1,
            }),
        }),
    }),
};

/*
 * Deze functie zorgt ervoor dat per feature (is Ã©Ã©n asset) een passende stijl wordt gekozen.
 */
var styleFunction = function (feature) {

    var asset;
    var properties = feature.getProperties();
    if (properties.category) {
        asset = properties.category.sub.name;
    } else {
        asset = properties.Asset;
    }

    asset = asset.trim(); // removes unwanted spaces

    if (styles[asset]) {
        var style = styles[asset];
    } else {
        console.log('Not found [' + asset + ']');
    }

    if (asset == "Breachlocation") {
        // For Breachlocations
        color = '#4b4c54';

        if (properties.state == 'selected') {
            color = '#D81B60';
        }

    } else {

        var waterHeight = (properties.waterheight) ? properties.waterheight : 0;
        var threshold = properties.threshold.threshold_real;

        var dangerInd = -threshold + waterHeight;

        var color;
        if (dangerInd > 0) {
            color = 'red';

        }
        else if (waterHeight > 0.1) {
            color = 'orange';
        }
        else if (waterHeight <= 0.1) {
            color = 'green';
        }
        else {
            //unknown state
            color = 'black';
        }
    }

    style.getText().getFill().setColor(color);

    return style;
};

// to make sure we do not request something after each click
var RequestLayersTimer;
// to save the geojson in order to update the points
var assetsList;

$.getJSON("assets_RD.geojson", function (data) {
    assetsList = data;
});

/*
 * Hier wordt de Map geconstrueerd
 */
var map = new ol.Map({
    target: 'map',
    layers: [
        // Eerste laag: OpenStreetMap tiles
        new ol.layer.Tile({source: new ol.source.OSM()}),
        // Tweede laag: rasterdata met overstromingsscenario
    ],
    view: new ol.View({
        // hier transformeert Proj4 de rasterdata
        center: ol.proj.transform(ol.extent.getCenter(imageExtent), 'EPSG:28992', 'EPSG:3857'),
        zoom: 12,
    }),
});

var overlay = new ol.Overlay({
    element: document.getElementById('popup-container'),
    positioning: 'bottom-center',
    offset: [0, -10]
});
map.addOverlay(overlay);

//var overstromingsLayer = new ol.layer.Image({});
changeOverstromingsSource('http://185.66.250.247:8080/geoserver/vitale-assets/wms', 'vitale-assets:00');

function changeOverstromingsSource(url, layer, scenario) {

    var layername = "";
    scenario = scenario || "null";
    url = url || "http://185.66.250.247:8080/geoserver/vitale-assets/wms";

    if (layer == undefined) {
        /*
        var currentCookieBreachLocationId = readCookie('breachlocation');
        if (currentCookieBreachLocationId < 10) {
            currentCookieBreachLocationId = '0' + currentCookieBreachLocationId;
        }
        var layer = 'vitale-assets:' + currentCookieBreachLocationId;
        */
        layername = 'vitale-assets:00';
    }

    if (layer.layername) {
        layername = layer.layername;
    }

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

    var overstromingsLayer = new ol.layer.Tile({
        source: overstromingsSource,
        name: "overstroming",
        zIndex: 30
    });
    removeLayerByName("overstroming");

    map.addLayer(overstromingsLayer);

    httpPOSTAsync('http://v-wcf032.directory.intra:5000/api/selectPoints', {
        layername: "vitale-assets:" + scenario + "_10m"
        , geojson: assetsList
    }, function (assets) {
        // add values to assets
        map.getLayers().forEach(function (layer) {
            if (layer.values_.name == 'assetLocations') {
                layer.getSource().forEachFeature(function (feature) {
                    feature.values_.waterheight = (assets.result[feature.values_.id] >= 0) ? assets.result[feature.values_.id] : 0;
                });
                layer.getSource().changed();
                return;
            }
        });
    })

}

function removeLayerByName(layerName) {
    map.getLayers().forEach(function (layer) {
        if (layer != undefined && layer.values_.name == layerName) {
            map.removeLayer(layer);
            return;
        }
    });
}

//click events on the map
map.on('click', function (e) {

    overlay.setPosition();
    var features = map.getFeaturesAtPixel(e.pixel);

    if (!features) {
        console.warn("No feature selected: " + e.pixel);
    }

    if (features) {
        var coordinate = e.coordinate;
        //var hdms = ol.coordinate.toStringHDMS(ol.proj.transform(coordinate, 'EPSG:3857', 'EPSG:4326'));

        var image = '';
        if (features[0].values_.image) {
            image = `<img style="width: 150px; display: block;" src="${features[0].values_.image}">`;
        }

        overlay.getElement().innerHTML = `<p>${features[0].values_.name}${image}</p>`;
        overlay.setPosition(coordinate);

        //for fadeout effect
        overlay.getElement().style.display = "block";

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

    if (features && features[0].values_.Asset == "Breachlocation") {
        /*
                // create cookie
                createCookie('breachlocation', features[0].values_.id, 7);
                */

        //set feature as selected
        if (features[0].values_.state && features[0].values_.state == 'selected') {
            features[0].values_.state = null;
        }
        else {
            features[0].values_.state = 'selected';
        }

        //get all features with a selected state
        var breachLocationsLayer = {};
        var requestLayerParams =
            {
                "scenarios": [],
                "ids": [],

            }
        map.getLayers().forEach(function (layer) {
            if (layer.values_.name == 'breachLocations') {
                layer.getSource().forEachFeature(function (feature) {
                    if (feature.values_.state == 'selected') {
                        //we can easily do it on Id or other parameter
                        requestLayerParams.scenarios.push(feature.values_.name.replace(/\s/g, '').toLowerCase() + "nwd");
                        requestLayerParams.ids.push(feature.values_.id);
                    }
                    ;
                });
                return;
            }
        });

        clearTimeout(RequestLayersTimer);
        //check if 0-1 or more
        if (requestLayerParams.scenarios.length == 1) {
            var layerName = 'vitale-assets:' + requestLayerParams.ids[0];
            //set timeout as to not need to request on each click
            RequestLayersTimer = setTimeout(changeOverstromingsSource, 2000, "http://185.66.250.247:8080/geoserver/vitale-assets/wms", layerName, requestLayerParams.scenarios[0]);
        }
        else if (requestLayerParams.scenarios.length > 1) {
            RequestLayersTimer = setTimeout(RequestMaxLayer, 2000, {
                scenarios: requestLayerParams.scenarios,
                "resolution": 10
            });
        }
        else {
            //clear waterheights
            map.getLayers().forEach(function (layer) {
                if (layer.values_.name == 'assetLocations') {
                    layer.getSource().forEachFeature(function (feature) {
                        feature.values_.waterheight = 0;
                    });
                    layer.getSource().changed();
                    return;
                }
            });
        }


        //reload map
        removeLayerByName("overstroming");
        map.getLayers().forEach(function (layer) {
            layer.getSource().changed();
        });
    }

});

function RequestMaxLayer(RequestParams) {
    //show loading screen

    //console.log(RequestParams.scenarios);

    httpPOSTAsync('http://v-wcf032.directory.intra:5000/api/calculateScenario', RequestParams, function (data) {
        //console.log(data.result);
        //clear layer
        var maxScenarioWMSLayer = changeOverstromingsSource(data.result.wms_url, data.result.layername, RequestParams.scenarios[0]);

    });
}

function httpGetAsync(URL, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status === 200) {
            callback(xhr.responseText);
        }
    }
    xhr.open("GET", URL, true); // true for asynchronous
    xhr.send();
}

function httpPOSTAsync(URL, jsonData, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', URL);
    xhr.setRequestHeader("Content-Type", "application/json; charset=UTF-8");
    xhr.onload = function () {
        if (xhr.readyState == 4 && xhr.status === 200) {
            var JSON = JSON.parse(xhr.responseText);
            if (JSON.result.err) {
                console.warn(result.err);
            }
            else {
                callback(JSON.parse(xhr.responseText));
            }

        }
    };

    xhr.send(JSON.stringify(jsonData));
}


/*
// Ik probeer hier de zoom aan te passen
//console.log(overstromingsLayer.getSource());
var mapExtent = ol.proj.transform(imageExtent, 'EPSG:28992', 'EPSG:3857');
map.getView().fit(mapExtent, map.getSize());
*/