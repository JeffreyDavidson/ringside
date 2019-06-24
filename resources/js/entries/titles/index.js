("use strict");

var TitlesDatatables = (function() {
    var initTitlesTable = function() {
        var table = $("#titles_table");

        // begin first table
        table.DataTable({
            responsive: true,

            ajax:
                "https://keenthemes.com/metronic/themes/themes/metronic/dist/preview/inc/api/datatables/demos/server.php",

            // DOM Layout settings
            dom: `<'row'<'col-sm-12'tr>>
			<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,

            lengthMenu: [5, 10, 25, 50],

            pageLength: 10,

            language: {
                lengthMenu: "Display _MENU_"
            },

            // Order settings
            order: [[1, "desc"]],

            columns: [
                { data: "id", title: "Title ID" },
                { data: "name", title: "Title" },
                { data: "slug", title: "Slug" },
                { data: "introduced_at", title: "Date Introduced" },
                { data: "status", title: "Status", render: renderStatusCell },
                { data: "Type", title: "Type", render: renderTypeCell },
                {
                    data: "Actions",
                    title: "Actions",
                    render: renderActionCell,
                    orderable: false,
                    responsivePriority: -1
                }
            ],

            headerCallback: function(thead, data, start, end, display) {
                thead.getElementsByTagName("th")[0].innerHTML = `
                    <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                        <input type="checkbox" value="" class="m-group-checkable">
                        <span></span>
                    </label>`;
            },

            columnDefs: [
                {
                    targets: 0,
                    width: "30px",
                    className: "dt-right",
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return `
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid">
                            <input type="checkbox" value="" class="m-checkable">
                            <span></span>
                        </label>`;
                    }
                },
                {
                    targets: 1,
                    render: function(data, type, full, meta) {
                        var status = {
                            1: { title: "Pending", class: "kt-badge--brand" },
                            2: {
                                title: "Delivered",
                                class: " kt-badge--danger"
                            },
                            3: {
                                title: "Canceled",
                                class: " kt-badge--primary"
                            },
                            4: {
                                title: "Success",
                                class: " kt-badge--success"
                            },
                            5: { title: "Info", class: " kt-badge--info" },
                            6: { title: "Danger", class: " kt-badge--danger" },
                            7: { title: "Warning", class: " kt-badge--warning" }
                        };
                        if (typeof status[data] === "undefined") {
                            return data;
                        }
                        return (
                            '<span class="kt-badge ' +
                            status[data].class +
                            ' kt-badge--inline kt-badge--pill">' +
                            status[data].title +
                            "</span>"
                        );
                    }
                },
                {
                    targets: 9,
                    render: function(data, type, full, meta) {
                        var status = {
                            1: { title: "Online", state: "danger" },
                            2: { title: "Retail", state: "primary" },
                            3: { title: "Direct", state: "success" }
                        };
                        if (typeof status[data] === "undefined") {
                            return data;
                        }
                        return (
                            '<span class="kt-badge kt-badge--' +
                            status[data].state +
                            ' kt-badge--dot"></span>&nbsp;' +
                            '<span class="kt-font-bold kt-font-' +
                            status[data].state +
                            '">' +
                            status[data].title +
                            "</span>"
                        );
                    }
                }
            ]
        });

        table.on("change", ".kt-group-checkable", function() {
            var set = $(this)
                .closest("table")
                .find("td:first-child .kt-checkable");
            var checked = $(this).is(":checked");

            $(set).each(function() {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this)
                        .closest("tr")
                        .addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this)
                        .closest("tr")
                        .removeClass("active");
                }
            });
        });

        table.on("change", "tbody tr .kt-checkbox", function() {
            $(this)
                .parents("tr")
                .toggleClass("active");
        });
    };

    return {
        //main function to initiate the module
        init: function() {
            initTitlesTable();
        }
    };
})();

jQuery(document).ready(function() {
    KTDatatablesBasicBasic.init();
});
