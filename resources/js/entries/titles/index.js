("use strict");

var table = $('[data-table="wrestlers.index"]');

// begin first table
table.DataTable({
    // DOM Layout settings
    // dom: `<'row'<'col-sm-12'tr>>
    // 		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

    // language: {
    //     lengthMenu: "Display _MENU_"
    // },

    // Order settings
    order: [[1, "desc"]],
    ajax: "/titles",
    columns: [
        { data: "id", title: "Title ID" },
        { data: "name", title: "Title" },
        { data: "slug", title: "Slug" },
        { data: "introduced_at", title: "Date Introduced" },
        { data: "status", title: "Status" },
        {
            data: "Actions",
            title: "Actions",
            orderable: false,
            responsivePriority: -1
        }
    ]
});
