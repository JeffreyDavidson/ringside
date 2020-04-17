"use strict";

const employedWrestlersTable = $('[data-table="employed_wrestlers.index"]');
const releasedWrestlersTable = $('[data-table="released_wrestlers.index"]');
const retiredWrestlersTable = $('[data-table="retired_wrestlers.index"]');
const rowCounter = $("#kt_subheader_total");
const searchInput = $("#generalSearch");
const statusDropdown = $("#status-dropdown");
const startedAtStart = $("#started_at_start");
const startedAtEnd = $("#started_at_end");

const filterData = {
    status: null,
    started_at: null
};

const updateFilters = () => {
    filterData.status = statusDropdown.val();
    if (startedAtStart.val() && startedAtEnd.val()) {
        filterData.started_at = [startedAtStart.val(), startedAtEnd.val()];
    } else if (startedAtStart.val()) {
        filterData.started_at = [startedAtStart.val()];
    } else {
        filterData.started_at = null;
    }

    employedWrestlersTable
        .dataTable()
        .api()
        .draw();

    releasedWrestlersTable
        .dataTable()
        .api()
        .draw();

    retiredWrestlersTable
        .dataTable()
        .api()
        .draw();
};

const clearFilters = () => {
    filterData.status = null;
    filterData.started_at = null;
    statusDropdown.val("");
    startedAtStart.val("");
    startedAtEnd.val("");
    table
        .dataTable()
        .api()
        .draw();
};

employedWrestlersTable.DataTable({
    ajax: {
        url: window.location.href,
        data(params) {
            params.status = filterData.status;
            params.started_at = filterData.started_at;
        },
        error: function(xhr, error, code) {
            console.log(JSON.parse(xhr.responseText.errors));
            console.log(xhr);
            console.log(error);
            new Noty({
                type: "error",
                layout: "topRight",
                text: JSON.parse(xhr.responseText.errors)
            }).show();
        }
    },
    columns: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "hometown", title: "Hometown" },
        {
            data: "employed_at",
            title: "Date Employed",
            searchable: false
        },
        { data: "status", title: "Status", searchable: false },
        {
            data: "action",
            title: "Action",
            orderable: false,
            responsivePriority: -1
        }
    ],
    initComplete(settings) {
        rowCounter.html(`${settings.fnRecordsTotal()} Total`);
    }
});

releasedWrestlersTable.DataTable({
    ajax: {
        url: window.location.href + '/released',
        error: function(xhr, error, code) {
            console.log(JSON.parse(xhr.responseText.errors));
            console.log(xhr);
            console.log(error);
            new Noty({
                type: "error",
                layout: "topRight",
                text: JSON.parse(xhr.responseText.errors)
            }).show();
        }
    },
    columns: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "hometown", title: "Hometown" },
        {
            data: "released_at",
            title: "Date Released",
            searchable: false
        },
        {
            data: "action",
            title: "Action",
            orderable: false,
            responsivePriority: -1
        }
    ]
});

retiredWrestlersTable.DataTable({
    ajax: {
        url: window.location.href + "/retired",
        error: function(xhr, error, code) {
            console.log(JSON.parse(xhr.responseText.errors));
            console.log(xhr);
            console.log(error);
            new Noty({
                type: "error",
                layout: "topRight",
                text: JSON.parse(xhr.responseText.errors)
            }).show();
        }
    },
    columns: [
        { data: "id", title: "ID" },
        { data: "name", title: "Name" },
        { data: "hometown", title: "Hometown" },
        {
            data: "retired_at",
            title: "Date Retired",
            searchable: false
        },
        {
            data: "action",
            title: "Action",
            orderable: false,
            responsivePriority: -1
        }
    ]
});

searchInput.on("keyup", () =>
    employedWrestlesTable
        .DataTable()
        .search(searchInput.val())
        .draw()
);

employedWrestlersTable.on("draw.dt", (e, settings) => {
    const searchTerm = employedWrestlersTable.DataTable().search();
    if (!searchTerm) {
        rowCounter.html(`${settings.fnRecordsTotal()} Total`);
    } else {
        rowCounter.html(`${settings.fnRecordsDisplay()} Matching Rows`);
    }
});

$("#applyFilters").click(() => updateFilters());
$("#clearFilters").click(() => clearFilters());
