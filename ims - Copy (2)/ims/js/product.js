$(document).ready(function() {
    const categoryUnits = {
        'Medicine': ['Grams', 'Box', 'Tablets', 'Pieces'],
        'Beverages': ['Ml', 'Liters', 'Packets', 'Bottles'],
        'Electronics': ['Pieces', 'Box', 'Units'],
        'Food': ['Small-size', 'Medium-size', 'Large-size', 'Pieces', 'Slices'],
    };

    $('#addProduct').click(function() {
        $('#productModal').modal('show');
        $('#productForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Product");
        $('#action').val("Add");
        $('#btn_action').val("addProduct");
        $('#unit').empty().append('<option value="">Select Unit</option>');  // Reset the units dropdown
        $('#unit').prop('disabled', false);  // Enable unit dropdown
    });

    var productData = $('#productList').DataTable({
        "lengthChange": false,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'listProduct' },
            dataType: "json"
        },
        "columnDefs": [{
            "targets": [0, 7],
            "orderable": false,
        }],
        "pageLength": 9,
        'rowCallback': function(row, data, index) {
            $(row).find('td').addClass('align-middle')
            $(row).find('td:eq(0), td:eq(7)').addClass('text-center')
        },
    });

    $(document).on('change', '#categoryid', function() {
        var categoryid = $('#categoryid').val();
        var btn_action = 'getCategoryBrand';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { categoryid: categoryid, btn_action: btn_action },
            success: function(data) {
                $('#brandid').html(data);
            }
        });

        // Update the units dropdown based on the selected category
        let selectedCategory = $(this).find("option:selected").text();
        let units = categoryUnits[selectedCategory] || [];
        let unitDropdown = $('#unit');

        // Clear existing options
        unitDropdown.empty();
        unitDropdown.append('<option value="">Select Unit</option>');  // Add default option

        if (units.length === 0) {
            // If no units available for the selected category, disable the dropdown
            unitDropdown.prop('disabled', true);
        } else {
            // Populate units based on the selected category
            unitDropdown.prop('disabled', false);
            units.forEach(function(unit) {
                unitDropdown.append('<option value="' + unit + '">' + unit + '</option>');
            });
        }
     });

    $(document).on('submit', '#productForm', function(event) {
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var formData = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            success: function(data) {
                $('#productForm')[0].reset();
                $('#productModal').modal('hide');
                $('#action').attr('disabled', false);
                productData.ajax.reload();
            }
        })
    });

    $(document).on('click', '.view', function() {
        var pid = $(this).attr("id");
        var btn_action = 'viewProduct';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { pid: pid, btn_action: btn_action },
            success: function(data) {
                $('#productViewModal').modal('show');
                $('#productDetails').html(data);
            }
        })
    });

    $(document).on('click', '.update', function() {
        var pid = $(this).attr("id");
        var btn_action = 'getProductDetails';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { pid: pid, btn_action: btn_action },
            dataType: "json",
            success: function(data) {
                $('#productModal').modal('show');
                $('#categoryid').val(data.categoryid);
                $('#brandid').html(data.brand_select_box);
                $('#brandid').val(data.brandid);
                $('#pname').val(data.pname);
                $('#pmodel').val(data.model);
                $('#description').val(data.description);
                $('#quantity').val(data.quantity);
                $('#base_price').val(data.base_price);
                $('#tax').val(data.tax);
                $('.modal-title').html("<i class='fa fa-edit'></i> Edit Product");
                $('#pid').val(pid);
                $('#action').val("Edit");
                $('#btn_action').val("updateProduct");

                // Populate the units dropdown based on the selected category for editing
                let selectedCategory = $('#categoryid option:selected').text();
                let units = categoryUnits[selectedCategory] || [];
                let unitDropdown = $('#unit');
                unitDropdown.empty();
                unitDropdown.append('<option value="">Select Unit</option>');  // Add default option

                if (units.length === 0) {
                    unitDropdown.prop('disabled', true);
                } else {
                    unitDropdown.prop('disabled', false);
                    units.forEach(function(unit) {
                        unitDropdown.append('<option value="' + unit + '">' + unit + '</option>');
                    });
                    $('#unit').val(data.unit);  // Set the correct unit
                }
            }
        })
    });

    $(document).on('click', '.delete', function() {
        var pid = $(this).attr("id");
        var status = $(this).data("status");
        var btn_action = 'deleteProduct';
        if (confirm("Are you sure you want to delete this product?")) {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { pid: pid, status: status, btn_action: btn_action },
                success: function(data) {
                    $('#alert_action').fadeIn().html('<div class="alert alert-info">' + data + '</div>');
                    productData.ajax.reload();
                }
            });
        } else {
            return false;
        }
    });
});
