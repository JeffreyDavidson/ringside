("use strict");

const renderStatusCell = (data, type, full, meta) => {
    const status = {
        0: { title: "Inactive" },
        1: { title: "Active" }
    };
    if (typeof status[data] === "undefined") {
        return data;
    }
    return status[data].title;
};

var table = $('[data-table="titles.index"]');

// begin first table
table.DataTable({
    // Order settings
    order: [[1, "desc"]],
    ajax: "/titles",
    columns: [
        { data: "id", title: "Title ID" },
        { data: "name", title: "Title" },
        { data: "slug", title: "Slug" },
        { data: "introduced_at", title: "Date Introduced" },
        { data: "is_active", title: "Status", render: renderStatusCell },
        {
            data: "action",
            title: "Actions",
            orderable: false,
            responsivePriority: -1
        }
    ],
    initComplete: function(settings, json) {
        $("#kt_subheader_total").html(settings.fnRecordsTotal() + " Total");
    }
});
