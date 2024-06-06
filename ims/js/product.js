$(document).ready(function() {
    const ProductUnits = {
        'Beverages': ['Ml', 'Liters', 'Packets', 'Bottles'],
        'Desserts': ['Grams', 'Pieces'],
        'Drinks': ['Ml', 'Liters', 'Cans'],
        'Snacks': ['Grams', 'Packets'],
        'Meal': ['Grams', 'Packets', 'Plates'],
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
            "targets": [0, 4],
            "orderable": false,
        }],
        "pageLength": 9,
        'rowCallback': function(row, data, index) {
            $(row).find('td').addClass('align-middle')
            $(row).find('td:eq(0), td:eq(6)').addClass('text-center')
        },
    });

    $(document).on('change', '#ptype', function() {
        var selectedProduct = $(this).find("option:selected").text();
        let units = ProductUnits[selectedProduct] || [];
        let unitDropdown = $('#unit');

        // Clear existing options
        unitDropdown.empty();
        unitDropdown.append('<option value="">Select Unit</option>');  // Add default option

        if (units.length === 0) {
            // If no units available for the selected product, disable the dropdown
            unitDropdown.prop('disabled', true);
        } else {
            // Populate units based on the selected product
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
        var pid = $('#pid').val();
    
        // Check if the product already exists
        var pname = $('#pname').val();
        var unit = $('#unit').val();
    
        if (productAlreadyExists(pname, unit, pid)) {
            alert('Warning: Product with the same name and unit already exists.');
            $('#action').attr('disabled', false);
            return;
        }
    
        // Proceed with adding the product
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
        });
    });
    
    function productAlreadyExists(productName, productUnit, productId) {
        var rows = productData.rows().data();
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            if (row[0] === productName && row[2] === productUnit && row[7] != productId) {
                return true;
            }
        }
        return false;
    }

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
                
                $('#pname').val(data.pname);
                $('#pexpire').val(data.expire);
                $('#description').val(data.description);
                $('#quantity').val(data.quantity);
                $('#base_price').val(data.base_price);
              
                $('.modal-title').html("<i class='fa fa-edit'></i> Edit Product");
                $('#pid').val(pid);
                $('#action').val("Edit");
                $('#btn_action').val("updateProduct");

                // Populate the units dropdown based on the selected product for editing
                let selectedProduct = $('#pname option:selected').text();
                let units = ProductUnits[selectedProduct] || [];
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
