$(document).ready(function () {

    //language settings
    var dutch = {
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
        "emptyTable": "Geen resultaat",
        "zeroRecords": "Geen zoekresultaten",
        "lengthMenu": "Selecteer _MENU_ resultaten",
        "info": "Pagina _PAGE_ van _PAGES_",
        "infoEmpty": "Geen items gevonden",
        "infoFiltered": "(gefiltered _MAX_ totale resultaten)"
    };

    //create a new datatable with a ajax request
    window.table = $('table.dashboard-asset-scenario-table').DataTable({
        language: dutch,
        "ajax": {
            url: '/scenario/asset/get',
            type: 'get',
            "dataSrc": function (data) {

                var returnData = [];
                for (var i = 0; i < data.length; i++) {

                    returnData.push({
                        'asset': `<a href="${data[i]['asset']['self']}">${data[i]['asset']['name']}</a>`,
                        'category': `<a href="${data[i]['category']['self']}"><i class="fa va-icon">&#x${data[i]['category']['symbol']}</i> ${data[i]['category']['name']}</a>`,
                        'state': `<i class="fa fa-circle" aria-hidden="true" style="color: ${data[i]['state_color']}"></i>`,
                        'water_depth': data[i]['water_depth'],
                        'correction': data[i]['threshold_correction'],
                        'type': data[i]['type'],
                    });
                }
                return returnData;
            },
        },
        "columns": [
            {"data": "asset"},
            {"data": "category"},
            {"data": "state", "orderable": false},
            {"data": "water_depth"},
            {"data": "correction"},
            {"data": "type"},
        ],
        "order": [[3, "desc"]], //default order
        "pageLength": 50,
    });

});