import $ from "jquery";
$.extend(true, $.fn.dataTable.defaults, {
    processing: true,
    serverSide: true,
    sortable: true,
    orderable: true,
    searchable: true,
    responsive: true,
    searchDelay: 300,
    pageLength: 10,
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search by any column value...",
        lengthMenu: "_MENU_"
    },
    columnDefs: [
        {
            targets: "_all",
            defaultContent: '<em class="text-muted">(none)</em>'
        }
    ],
    lengthMenu: [
        [10, 25, 50, 100],
        ["10 per page", "25 per page", "50 per page", "100 per page"]
    ],
    dom:
        "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
});
