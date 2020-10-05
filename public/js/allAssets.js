window.addEventListener('load', ajaxCall);

function ajaxCall() {
    $.getJSON("assets/json")
        .done(loadData)
        .fail(function () {
            console.log("fail")
        });
}

function loadData(data) {
    var table = document.getElementById("body");

    var content = "";
    for (var i = 0; i < data.length; i++) {
        content += "<tr><td>" +
            data[i].id +
            "</td><td>" +
            "<a href='assets/" + data[i].id + "'>" + errorHandling(data[i].name) + "</a>" +
            "</td><td>"
            + errorHandling(data[i].description) +
            "</td><td>"
            + "<a href='categories/" + data[i].category.id + "'>" + errorHandling(data[i].category.name) + "</a>" +
            "</td><td>"
            + errorHandling(data[i].threshold_correction) +
            "</td><td class='align-content-center'>" +
            "<a href='/assets/" + data[i].id + "/edit' class='btn btn-warning btn-sm'>Wijzigen</a>" +
            " " +
            "<a href='/assets/delete/" + data[i].id + "' class='btn btn-danger btn-sm'>Verwijderen</a>" +
            "</td></tr>";
    }

    function errorHandling(data) {
        console.log(data);
        var returnMessage = "";
        if (data == null) {
            returnMessage = "Geen";
        } else {
            returnMessage = data;
        }
        return returnMessage;
    }

    table.innerHTML = content;

    $('#assets-table').DataTable({
        language: {
            "aria": {
                "sortAscending": ": activeer kolom sorteren oplopend",
                "sortDescending": ": activeer kolom sorteren afdelend"
            },
            "decimal": "",
            "infoPostFix": "",
            "paginate": {
                "first": " Eerste",
                "last": " Laatste ",
                "next": " Volgende ",
                "previous": " Vorige "
            },
            "thousands": ",",
            "search": "Zoeken:",
            "processing": "Zoeken naar resultaten...",
            "loadingRecords": "Laden van resultaten..",
            "emptyTable": "Geen assets gevonden",
            "zeroRecords": "Geen zoekresultaten",
            "lengthMenu": "Selecteer _MENU_ resultaten",
            "info": "Pagina _PAGE_ van _PAGES_",
            "infoEmpty": "Geen scenario's gevonden",
            "infoFiltered": "(gefiltered _MAX_ totale resultaten)"
        },
        columnDefs: [{
            "orderable": false,
            "targets": 6
        }],
        "pageLength": 100
    });
}
