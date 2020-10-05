var xCoordHidden = document.getElementById('xCoordHidden').value;
var yCoordHidden = document.getElementById('yCoordHidden').value;

var xCoord = Number(xCoordHidden);
var yCoord = Number(yCoordHidden);

var iconFeature = new ol.Feature({
    geometry: new ol.geom.Point([xCoord, yCoord]),
});

var iconStyle = new ol.style.Style({
    text: new ol.style.Text({
        text: "\uf041",
        font: "normal 32px FontAwesome",
        textBaseline: 'bottom',
        fill: new ol.style.Fill({
            color: '#dd4b39',
        }),
        stroke: new ol.style.Stroke({
            color: 'black',
            width: 1,
        }),
    }),
});

iconFeature.setStyle(iconStyle);

var vectorSource = new ol.source.Vector({
    features: [iconFeature]
});

var vectorLayer = new ol.layer.Vector({
    source: vectorSource
});

var tile = new ol.layer.Tile({
    source: new ol.source.OSM()
});

var map = new ol.Map({
    layers: [tile, vectorLayer],
    target: document.getElementById('map'),
    view: new ol.View({
        center: [xCoord, yCoord],
        zoom: 12
    })
});

//single click on map and change the marker and update fields
map.on('singleclick', function (evt) {
    //change input fields
    document.getElementById('xCoordinate').value = evt.coordinate[0];
    document.getElementById('yCoordinate').value = evt.coordinate[1];

    //change location icon
    iconFeature.getGeometry().setCoordinates(evt.coordinate);
});

//coord x and y fields and update map
var xCoordField = document.getElementById('xCoordinate');
var yCoordField = document.getElementById('yCoordinate');

xCoordField.addEventListener('keyup', function (e) {
    iconFeature.getGeometry().setCoordinates([xCoordField.value, yCoordField.value]);
    map.getView().setCenter([xCoordField.value, yCoordField.value]);
});

yCoordField.addEventListener('keyup', function (e) {
    iconFeature.getGeometry().setCoordinates([xCoordField.value, yCoordField.value]);
    map.getView().setCenter([xCoordField.value, yCoordField.value]);
});

//reset x and y coords
var resetButton = document.getElementById('resetButton');
resetButton.addEventListener('click', function (e) {

    //set x and y value
    var x = xCoordHidden;
    var y = yCoordHidden;

    //Change the value from the input fields
    document.getElementById('xCoordinate').value = x;
    document.getElementById('yCoordinate').value = y;

    //update the map
    iconFeature.getGeometry().setCoordinates([x, y]);
    map.getView().setCenter([x, y]);
});

//get user location
var myCurrentLocationButton = document.getElementById('myCurrentLocationButton');
var errorContainer = document.getElementById('map-error');

myCurrentLocationButton.addEventListener('click', function(e) {
    //check for Geolocation support
    if (navigator.geolocation) {
        var startPos;
        var geoSuccess = function(position) {
            //set position
            startPos = position;

            //convert lon lat to x and y coords
            var newPos = ol.proj.transform([startPos.coords.longitude, startPos.coords.latitude], 'EPSG:4326','EPSG:3857');

            //update input fields
            document.getElementById('xCoordinate').value = newPos[0];
            document.getElementById('yCoordinate').value = newPos[1];

            //update map
            iconFeature.getGeometry().setCoordinates([newPos[0],newPos[1]]);
            map.getView().setCenter([newPos[0],newPos[1]]);
        };

        var geoError = function(error) {

            switch(error.code) {
                case 0:
                    errorContainer.innerHTML = `<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b>Foutmelding:</b> onbekende fout.
                            </div>`;
                    break;
                case 1:
                    errorContainer.innerHTML = `<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b>Foutmelding:</b> geen toestemming.
                            </div>`;
                    break;
                case 2:
                    errorContainer.innerHTML = `<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b>Foutmelding:</b> positie niet beschikbaar.
                            </div>`;
                    break;
                case 3:
                    errorContainer.innerHTML = `<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b>Foutmelding:</b> timed out.
                            </div>`;
                    break;
            }

        };

        navigator.geolocation.getCurrentPosition(geoSuccess, geoError);
    }
    else {
        //Geolocation is not supported for this Browser/OS.
        errorContainer.innerHTML = `<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b>Foutmelding:</b> Geolocatie wordt niet ondersteund door deze browser / besturingssysteem.
                            </div>`;
    }

});

//Map information tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});