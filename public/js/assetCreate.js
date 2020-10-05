//default x and y coords (Amsterdam)
var xCoord = 544503.5418960674;
var yCoord = 6865678.276837627;

//If the page gets refreshed remember the old x and y coord values from the hidden input
if (document.getElementById('xCoordHidden').value) {
    xCoord = Number(document.getElementById('xCoordHidden').value);
}
if (document.getElementById('yCoordHidden').value) {
    yCoord = Number(document.getElementById('yCoordHidden').value);
}

//create an icon with point x anc y
var iconFeature = new ol.Feature({
    geometry: new ol.geom.Point([xCoord, yCoord]),
});

//create and icon style
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

//set the icon style
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

//create the openlayers map
var map = new ol.Map({
    layers: [tile, vectorLayer],
    target: document.getElementById('map'),
    view: new ol.View({
        center: [xCoord, yCoord],
        zoom: 7
    })
});

//if the user clicks on the map change the icon location with it
map.on('singleclick', function (evt) {
    //change input fields
    document.getElementById('xCoordinate').value = evt.coordinate[0];
    document.getElementById('yCoordinate').value = evt.coordinate[1];

    //change location icon
    iconFeature.getGeometry().setCoordinates(evt.coordinate);
});

//define the x and y coord fields
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

//get user location
var myCurrentLocationButton = document.getElementById('myCurrentLocationButton');
var errorContainer = document.getElementById('map-error');

myCurrentLocationButton.addEventListener('click', function (e) {
    //check for Geolocation support
    if (navigator.geolocation) {
        var startPos;
        var geoSuccess = function (position) {
            //set position
            startPos = position;

            //convert lon lat to x and y coords
            var newPos = ol.proj.transform([startPos.coords.longitude, startPos.coords.latitude], 'EPSG:4326', 'EPSG:3857');

            //update input fields
            document.getElementById('xCoordinate').value = newPos[0];
            document.getElementById('yCoordinate').value = newPos[1];

            //update map
            iconFeature.getGeometry().setCoordinates([newPos[0], newPos[1]]);
            map.getView().setCenter([newPos[0], newPos[1]]);
        };

        var geoError = function (error) {

            var errorMessage = '';

            switch (error.code) {
                case 0:
                    errorMessage = 'onbekende fout.';
                    break;
                case 1:
                    errorMessage = 'geen toestemming.';
                    break;
                case 2:
                    errorMessage = 'positie niet beschikbaar.';
                    break;
                case 3:
                    errorMessage = 'timed out.';
                    break;
            }

            errorContainer.innerHTML = `<div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <b>Foutmelding:</b> ${errorMessage}
                            </div>`;
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