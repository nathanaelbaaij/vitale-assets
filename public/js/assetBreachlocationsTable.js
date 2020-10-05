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
    window.table = $('table.asset-breachlocation-waterdepth-table').DataTable({
        language: dutch,
        "ajax": {
            url: '/assets/floatscenarios',
            type: 'get',
            data: {
                loadLevelId: 2, //default tab
                assetId: assetId
            },
            "dataSrc": function (json) {
                for (var i = 0, ien = json.data.length; i < ien; i++) {
                    json.data[i][2] = `<i class="fa fa-circle" aria-hidden="true" style="color: ${json.data[i][2]}"></i>`;
                }
                return json.data;
            },
        },
        "order": [[1, "desc"]], //default order waterdepth on desc
        "pageLength": 50,
    });

    //when the user changes a tab
    $('[data-toggle="tab"]').on('click', function (e) {

        var $this = $(this);
        var loadLevelId = $this.attr('data-loadlevel'); //define the loadlevel

        //destroy the last table and make a new one with the new json
        window.table.destroy();
        window.table = $('table.asset-breachlocation-waterdepth-table').DataTable({
            language: dutch,
            "ajax": {
                url: '/assets/floatscenarios',
                type: 'get',
                data: {
                    loadLevelId: loadLevelId,
                    assetId: assetId
                },
                "dataSrc": function (json) {
                    for (var i = 0, ien = json.data.length; i < ien; i++) {
                        var color = '';
                        switch (json.data[i][2]) {
                            case 0:
                                color = 'green';
                                break;
                            case 1:
                                color = 'orange';
                                break;
                            case 2:
                                color = 'red';
                                break;
                            default:
                                color = 'black';
                                break;
                        }
                        json.data[i][2] = `<i class="fa fa-circle" aria-hidden="true" style="color: ${color}"></i>`;
                    }
                    return json.data;
                }
            },
            "order": [[1, "desc"]],
            "pageLength": 50
        });

    });
});