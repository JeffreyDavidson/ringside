import $ from "jquery";

$("[data-datetimepicker]").datetimepicker({
    todayHighlight: true,
    autoclose: true,
    pickerPosition: "bottom-left",
    format: "yyyy/mm/dd hh:ii"
});
