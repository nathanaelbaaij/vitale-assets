window.addEventListener('load', ajaxCall);

/**
 *
 */
function ajaxCall() {
    if(document.getElementById('asset_id')) {
        var assetId = document.getElementById('asset_id').value;
        $.getJSON("/properties/" + assetId + "")
            .done(loadProperties)
            .fail(function () {
                console.log("fail")
            });
    }
}

var propertiesArray = [];
var propertyNumber = 1;

/**
 *
 * @param data
 */
function loadProperties(data) {
    //console.log(data);
    for (var i = 0; i < data.length; i++) {
        console.log(data[i]);
        addProperty(data[i].propertyName, data[i].propertyvalue, data[i].assetId, data[i].propertyId);
    }
}

/**
 *
 * @param propertyName
 * @param propertyvalue
 * @param assetId
 * @param propId
 */
function addProperty(propertyName, propertyvalue, assetId, propId) {
    //console.log(propertyName + " " + propertyvalue)
    document.getElementById("inputproperties").innerHTML += '' +
        '<div class="row" id="property' + propertyNumber + '">' +
        '   <div class="col-lg-6" >' +
        '       <input name="property[' + (propertyNumber - 1) + '][name]"+ type="text" class="form-control" id="' + propertyNumber + '" placeholder="Eigenschap" ' + checkIfData(propertyName, "name") + '>' +
        '  </div>' +
        '   <div class="col-lg-6" >' +
        '       <div style="margin-bottom: 10px" class="input-group"> ' +
        '           <input name="property[' + (propertyNumber - 1) + '][value]"+ type="text" class="form-control" id="' + propertyNumber + '" placeholder="Waarde" ' + checkIfData(propertyvalue, "value") + '>' +
        '           <span class="input-group-addon btn" onclick="deleteProperty(' + propertyNumber + ',' + propId + ',' + assetId + ',)">' +
        '               <i class="fa fa-minus"></i> ' +
    '           </span>' +
    '       </div>' +
    '   </div>' +
    '</div>';
    propertyNumber++;
    propertiesArray.push([propertyName, propertyvalue]);
}

/**
 *
 * @param data
 * @param type
 * @returns {string}
 */
function checkIfData(data, type) {
    if (data) {
        if (type == "name") {
            return ('value="' + data + '" readonly');
        } else {
            return ('value="' + data + '"');
        }
    }
}

/**
 *
 * @param delProp
 * @param propId
 * @param assetId
 */
function deleteProperty(delProp, propId, assetId) {
    var propertyDom = document.getElementById("property" + delProp);
    document.getElementById("inputproperties").removeChild(propertyDom);
    propertyNumber--;
    if (propId != null) {
        ajaxDelete(assetId, propId);
    }
}

/**
 *
 * @param assetId
 * @param propId
 */
function ajaxDelete(assetId, propId) {
    var link = '/assets/' + assetId + '/delete/' + propId
    console.log(link);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: link,
        type: 'DELETE',
        dataType: "json",
    }).done(function (data) {
        alert(JSON.stringify(data));
    });
}