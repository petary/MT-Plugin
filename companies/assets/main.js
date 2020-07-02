var $ = jQuery;
$(document).ready(function () {
    $('#table_id').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: tables_to_export
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: tables_to_export
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: tables_to_export
                }
            }
        ],
    });

    $(function () {
        $("#warrantyStart").datepicker();
        $("#warrantyStart").datepicker("option", "dateFormat", 'dd-mm-yy');
        if (start_date) {
            $("#warrantyStart").datepicker("setDate", start_date);
        }

        $("#warrantyEnd").datepicker();
        $("#warrantyEnd").datepicker("option", "dateFormat", 'dd-mm-yy');
        if (end_date) {
            $("#warrantyEnd").datepicker("setDate", end_date);
        }
    });





});