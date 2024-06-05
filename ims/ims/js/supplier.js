$(document).ready(function() {
    $('#addSupplier').click(function() {
        $('#supplierModal').modal('show');
        $('#supplierForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Customer");
        $('#action').val("Add");
        $('#btn_action').val("addSupplier");
    });
    
    var supplierDataTable = $('#supplierList').DataTable({
        "lengthChange": false,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'supplierList' },
            dataType: "json"
        },
        "columnDefs": [{
            "target": [0, 5],
            "orderable": false
        }],
        "pageLength": 25,
        'rowCallback': function(row, data, index) {
            $(row).find('td').addClass('align-middle')
            $(row).find('td:eq(0), td:eq(4), td:eq(5)').addClass('text-center')
        },
    });

    $(document).on('submit', '#supplierForm', function(event) {
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            success: function(data) {
                $('#supplierForm')[0].reset();
                $('#supplierModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success">' + data + '</div>');
                $('#action').attr('disabled', false);
                supplierDataTable.ajax.reload();
            }
        });
    });

    $(document).on('click', '.update', function() {
        var supplier_id = $(this).attr("id");
        var btn_action = 'getSupplier';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { supplier_id: supplier_id, btn_action: btn_action },
            dataType: "json",
            success: function(data) {
                $('#supplierModal').modal('show');
                $('#supplier_name').val(data.supplier_name);
                $('#address').val(data.address);
                $('#mobile').val(data.mobile);
                $('.modal-title').html("<i class='fa fa-edit'></i> Edit Supplier");
                $('#supplier_id').val(supplier_id);
                $('#action').val('Update');
                $('#btn_action').val('updateSupplier');
            }
        });
    });

    $(document).on('click', '.delete', function() {
        var supplier_id = $(this).attr("id");
        var btn_action = "deleteSupplier";
        if (confirm("Are you sure you want to delete this supplier?")) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { supplier_id: supplier_id, btn_action: btn_action },
                success: function(data) {
                    supplierDataTable.ajax.reload();
                }
            });
        } else {
            return false;
        }
    });

    $('#exportButton').click(function() {
        var format = $('#exportFormat').val();
        var exportFunction;
        switch (format) {
            case 'csv':
                exportFunction = exportToCSV;
                break;
            case 'pdf':
                exportFunction = exportToPDF;
                break;
            case 'text':
                exportFunction = exportToText;
                break;
            default:
                break;
        }
        if (exportFunction) {
            exportFunction();
        }
    });

    function exportToCSV() {
        // Logic to export data to CSV format
        var csv = '';
        var rows = $('#supplierList').find('tr');
        rows.each(function(index, row) {
            $(row).find('th, td').each(function(index, cell) {
                csv += $(cell).text() + ',';
            });
            csv += '\n';
        });
        downloadFile(csv, 'supplier_list.csv', 'text/csv');
    }

    function exportToPDF() {
        // Logic to export data to PDF format
        // Implementation dependent on the library you choose for PDF generation
    }

    function exportToText() {
        // Logic to export data to Text format
        var text = '';
        var rows = $('#supplierList').find('tr');
        rows.each(function(index, row) {
            $(row).find('th, td').each(function(index, cell) {
                text += $(cell).text() + '  ';
            });
            text += '\n';
        });
        downloadFile(text, 'supplier_list.txt', 'text/plain');
    }

    function downloadFile(data, filename, type) {
        var file = new Blob([data], {type: type});
        if (window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(file, filename);
        } else {
            var a = document.createElement('a');
            var url = URL.createObjectURL(file);
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            setTimeout(function() {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);  
            }, 0);
        }
    }
});
