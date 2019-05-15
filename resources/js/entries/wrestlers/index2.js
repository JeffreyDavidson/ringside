const table = $('[data-table="wrestlers.index"]');

table.dataTable({
    ajax: window.location.href,
    columns: [
        {
            name: "name",
            data: "name",
            title: "Name",
            className: "user-avatar user-info"
        },
        {
            name: "email",
            data: "email",
            title: "Email"
        },
        {
            name: "roles.name",
            data: "roles",
            title: "Roles",
            render: roleCell,
            orderable: false
        },
        {
            name: "action",
            data: "action",
            title: "",
            className: "text-right",
            searchable: false,
            orderable: false
        }
    ]
});
