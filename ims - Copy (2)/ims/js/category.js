    $(document).ready(function() {
        $('#categoryAdd').click(function() {
            $('#categoryForm')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'></i> Add Category");
            $('#action').val('Add');
            $('#btn_action').val('categoryAdd');
            $('#customFieldsContainer').html('');
        });

        var categoryData = $('#categoryList').DataTable({
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "action.php",
                type: "POST",
                data: { action: 'categoryList' },
                dataType: "json"
            },
            "columnDefs": [{
                "targets": [0, 2],
                "orderable": false,
            }, ],
            "pageLength": 25,
            'rowCallback': function(row, data, index) {
                $(row).find('td').addClass('align-middle')
                $(row).find('td:eq(0), td:eq(2)').addClass('text-center')
            }
        });

        $('#addCustomField').click(function() {
            $('#customFieldsContainer').append(getCustomFieldHtml());
        });

        // Remove custom field
        $(document).on('click', '.removeCustomField', function() {
            $(this).closest('.customField').remove();
        });
        
        $(document).on('submit', '#categoryForm', function(event) {
            event.preventDefault();
            $('#action').attr('disabled', 'disabled');
            var formData = $(this).serialize();
            $.ajax({
                url: "action.php",
                method: "POST",
                data: formData,
                success: function(data) {
                    $('#categoryForm')[0].reset();
                    $('#categoryModal').modal('hide');
                    $('#alert_action').fadeIn().html('<div class="alert alert-success">' + data + '</div>');
                    $('#action').attr('disabled', false);
                    categoryData.ajax.reload();
                }
            })
        });
        $(document).on('click', '.update', function() {
            var categoryId = $(this).attr("id");
            var btnAction = 'getCategory';
            $.ajax({
                url: "action.php",
                method: "POST",
                data: { categoryId: categoryId, btn_action: btnAction },
                dataType: "json",
                success: function(data) {
                    $('#categoryModal').modal('show');
                    $('#category').val(data.name);
                    $('.modal-title').html("<i class='fa fa-edit'></i> Edit Category");
                    $('#categoryId').val(categoryId);
                    $('#action').val('Edit');
                    $('#btn_action').val("updateCategory");
                }
            })
        });
        $(document).on('click', '.delete', function() {
            var categoryId = $(this).attr('id');
            var status = $(this).data("status");
            var btn_action = 'deleteCategory';
            if (confirm("Are you sure you want to delete this category?")) {
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data: { categoryId: categoryId, status: status, btn_action: btn_action },
                    success: function(data) {
                        $('#alert_action').fadeIn().html('<div class="alert alert-info">' + data + '</div>');
                        categoryData.ajax.reload();
                    }
                })
            } else {
                return false;
            }
        });

        function getCustomFieldHtml() {
            return `
                <div class="customField mb-3">
                    <label>Field Name</label>
                    <input type="text" name="customFieldName[]" class="form-control rounded-0" required />
                    <label>Field Type</label>
                    <select name="customFieldType[]" class="form-select rounded-0" required>
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                    </select>
                    <label>Field Value</label>
                    <input type="text" name="customFieldValue[]" class="form-control rounded-0" required />
                    <button type="button" class="btn btn-danger btn-sm removeCustomField mt-2">Remove Field</button>
                </div>
            `;
        }

        function getCustomFieldHtml(fieldName = '', fieldType = 'text', fieldValue = '') {
            return `
                <div class="customField mb-3">
                    <label>Field Name</label>
                    <input type="text" name="customFieldName[]" class="form-control rounded-0" value="${fieldName}" required />
                    <label>Field Type</label>
                    <select name="customFieldType[]" class="form-select rounded-0" required>
                        <option value="text" ${fieldType === 'text' ? 'selected' : ''}>Text</option>
                        <option value="number" ${fieldType === 'number' ? 'selected' : ''}>Number</option>
                        <option value="date" ${fieldType === 'date' ? 'selected' : ''}>Date</option>
                    </select>
                        
                    <label>Field Value</label>
                    <input type="text" name="customFieldValue[]" class="form-control rounded-0" value="${fieldValue}" required />
                    <button type="button" class="btn btn-danger btn-sm removeCustomField mt-2">Remove Field</button>
                </div>
            `;
        }
    });