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

    //assets/index.blade.php
    $('#assets-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": false, "targets": 2 }
        ],
        "pageLength": 100
    });

    //breaches/index.blade.php
    $('#breachlocations-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": false, "targets": 8 }
        ],
        "pageLength": 100
    });

    //cacades/index.blade.php
    $('#cascades-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": true, "targets": 3 }
        ],
        "pageLength": 100
    });

    //consequences/partials/related.blade.php
    $('#consequence-cascades-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": true, "targets": 2 }
        ],
        "pageLength": 100
    });

    //consequences/index.blade.php
    $('#consequences-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": true, "targets": 1 }
        ],
        "pageLength": 100
    });

    //loadlevels/index.blade.php
    $('#loadlevels-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ],
        "pageLength": 100
    });

    //scenarios/index.blade.php
    $('#scenarios-table').DataTable({
        language: dutch,
        "order": [[2, "desc"]], //default order
        "pageLength": 50
    });

    //invites/index.blade.php
    $('#invites-table').DataTable({
        language: dutch,
        "columnDefs": [
            { "orderable": false, "targets": 4 }
        ],
        "pageLength": 100
    });

    //breaches/index.blade.php
    $('#category-assets-table').DataTable({
        language: dutch,
        // "columnDefs": [
        //     { "orderable": false, "targets": 8 }
        // ],
        "pageLength": 10
    });

    //others...
});