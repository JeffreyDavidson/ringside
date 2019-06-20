"use strict";
// Class definition

var KTAppVenueListDatatable = (function() {
    // variables
    var datatable;

    // init
    var init = function() {
        datatable = $("#kt_apps_venue_list_datatable").KTDatatable({
            // datasource definition
            data: {
                type: "remote",
                source: {
                    read: {
                        url: "/venues",
                        method: "GET",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            )
                        }
                    }
                },
                pageSize: 10, // display 20 records per page
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                footer: false // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $("#generalSearch"),
                delay: 400
            },

            // columns definition
            columns: [
                {
                    field: "RecordID",
                    title: "#",
                    sortable: false,
                    width: 20,
                    selector: {
                        class: "kt-checkbox--solid"
                    },
                    textAlign: "center"
                },
                {
                    field: "name",
                    title: "Venue",
                    width: 200
                },
                {
                    field: "address",
                    title: "Address",
                    template: function(row) {
                        return row.address1 + " " + row.address2;
                    }
                },
                {
                    field: "city",
                    title: "City"
                },
                {
                    field: "state",
                    title: "State"
                },
                {
                    field: "zip",
                    title: "Zip"
                },
                {
                    field: "actions",
                    width: 80,
                    title: "Actions",
                    sortable: false,
                    autoHide: false,
                    overflow: "visible"
                }
            ]
        });
    };

    // search
    var search = function() {
        $("#kt_form_status").on("change", function() {
            datatable.search(
                $(this)
                    .val()
                    .toLowerCase(),
                "Status"
            );
        });
    };

    // selection
    var selection = function() {
        // init form controls
        //$('#kt_form_status, #kt_form_type').selectpicker();

        // event handler on check and uncheck on records
        datatable.on(
            "kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated",
            function(e) {
                var checkedNodes = datatable
                    .rows(".kt-datatable__row--active")
                    .nodes(); // get selected records
                var count = checkedNodes.length; // selected records count

                $("#kt_subheader_group_selected_rows").html(count);

                if (count > 0) {
                    $("#kt_subheader_search").addClass("kt-hidden");
                    $("#kt_subheader_group_actions").removeClass("kt-hidden");
                } else {
                    $("#kt_subheader_search").removeClass("kt-hidden");
                    $("#kt_subheader_group_actions").addClass("kt-hidden");
                }
            }
        );
    };

    // fetch selected records
    var selectedFetch = function() {
        // event handler on selected records fetch modal launch
        $("#kt_datatable_records_fetch_modal")
            .on("show.bs.modal", function(e) {
                // show loading dialog
                var loading = new KTDialog({
                    type: "loader",
                    placement: "top center",
                    message: "Loading ..."
                });
                loading.show();

                setTimeout(function() {
                    loading.hide();
                }, 1000);

                // fetch selected IDs
                var ids = datatable
                    .rows(".kt-datatable__row--active")
                    .nodes()
                    .find('.kt-checkbox--single > [type="checkbox"]')
                    .map(function(i, chk) {
                        return $(chk).val();
                    });

                // populate selected IDs
                var c = document.createDocumentFragment();

                for (var i = 0; i < ids.length; i++) {
                    var li = document.createElement("li");
                    li.setAttribute("data-id", ids[i]);
                    li.innerHTML = "Selected record ID: " + ids[i];
                    c.appendChild(li);
                }

                $(e.target)
                    .find("#kt_apps_user_fetch_records_selected")
                    .append(c);
            })
            .on("hide.bs.modal", function(e) {
                $(e.target)
                    .find("#kt_apps_user_fetch_records_selected")
                    .empty();
            });
    };

    // selected records delete
    var selectedDelete = function() {
        $("#kt_subheader_group_actions_delete_all").on("click", function() {
            // fetch selected IDs
            var ids = datatable
                .rows(".kt-datatable__row--active")
                .nodes()
                .find('.kt-checkbox--single > [type="checkbox"]')
                .map(function(i, chk) {
                    return $(chk).val();
                });

            if (ids.length > 0) {
                // learn more: https://sweetalert2.github.io/
                swal.fire({
                    buttonsStyling: false,

                    text:
                        "Are you sure to delete " +
                        ids.length +
                        " selected records ?",
                    type: "danger",

                    confirmButtonText: "Yes, delete!",
                    confirmButtonClass: "btn btn-sm btn-bold btn-danger",

                    showCancelButton: true,
                    cancelButtonText: "No, cancel",
                    cancelButtonClass: "btn btn-sm btn-bold btn-brand"
                }).then(function(result) {
                    if (result.value) {
                        swal.fire({
                            title: "Deleted!",
                            text: "Your selected records have been deleted! :(",
                            type: "success",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            confirmButtonClass: "btn btn-sm btn-bold btn-brand"
                        });
                        // result.dismiss can be 'cancel', 'overlay',
                        // 'close', and 'timer'
                    } else if (result.dismiss === "cancel") {
                        swal.fire({
                            title: "Cancelled",
                            text:
                                "You selected records have not been deleted! :)",
                            type: "error",
                            buttonsStyling: false,
                            confirmButtonText: "OK",
                            confirmButtonClass: "btn btn-sm btn-bold btn-brand"
                        });
                    }
                });
            }
        });
    };

    var updateTotal = function() {
        datatable.on("kt-datatable--on-layout-updated", function() {
            //$('#kt_subheader_total').html(datatable.getTotalRows() + ' Total');
        });
    };

    return {
        // public functions
        init: function() {
            init();
            search();
            selection();
            selectedFetch();
            selectedDelete();
            updateTotal();
        }
    };
})();

// On document ready
KTUtil.ready(function() {
    KTAppVenueListDatatable.init();
});
