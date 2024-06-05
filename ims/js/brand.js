$(document).ready(function() {
    $('#addBrand').click(function() {
        $('#brandModal').modal('show');
        $('#brandForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Brand");
        $('#action').val('Add');
        $('#btn_action').val('addBrand');
    });

    var branddataTable = $('#brandList').DataTable({
        "lengthChange": false,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'listBrand' },
            dataType: "json"
        },
        "columnDefs": [{
            "targets": [0, 3],
            "orderable": false,
        }, ],
        "pageLength": 10,
        'rowCallback': function(row, data, index) {
            $(row).find('td').addClass('align-middle')
            $(row).find('td:eq(0), td:eq(3)').addClass('text-center')
        },
    });


    $(document).on('submit', '#brandForm', function(event) {
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            success: function(data) {
                $('#brandForm')[0].reset();
                $('#brandModal').modal('hide');
                $('#action').attr('disabled', false);
                branddataTable.ajax.reload();
            }
        })
    });

    $(document).on('click', '.update', function() {
        var row = $(this).closest('tr');
        var rowData = branddataTable.row(row).data();
        var btn_action = 'getBrand';
        $.ajax({
            url: 'action.php',
            method: "POST",
            data: { bname: rowData[0], btn_action: btn_action },
            dataType: "json",
            success: function(data) {
                $('#brandModal').modal('show');
                $('#categoryid').val(data.categoryid);
                $('#bname').val(data.bname);
                $('.modal-title').html("<i class='fa fa-edit'></i> Edit Brand");
                $('#id').val(data.id); // Ensure you have a hidden field for id if needed
                $('#action').val('Edit');
                $('#btn_action').val('updateBrand');
            }
        })
    });

    $(document).on('click', '.delete', function() {
        var row = $(this).closest('tr');
        var rowData = branddataTable.row(row).data();
        var status = $(this).data('status');
        var btn_action = 'deleteBrand';
        if (confirm("Are you sure you want to delete this brand?")) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { bname: rowData[0], status: status, btn_action: btn_action },
                success: function(data) {
                    branddataTable.ajax.reload();
                }
            })
        } else {
            return false;
        }
    });

});
