$(document).ready(function() {
    // Display alert message
    function showAlert(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('#alertContainer').html(alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }
    
    // Form validation
    function validateForm(formId) {
        const form = document.getElementById(formId);
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return false;
        }
        return true;
    }
    
    // Add product form submission
    $('#saveNewProduct').click(function() {
        if (!validateForm('addProductForm')) {
            return;
        }
        
        $.ajax({
            url: 'products.php',
            type: 'POST',
            data: $('#addProductForm').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#saveNewProduct').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    $('#addProductModal').modal('hide');
                    showAlert(response.message);
                    // Reload page to show updated product list
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                showAlert('An error occurred while processing your request.', 'danger');
            },
            complete: function() {
                $('#saveNewProduct').prop('disabled', false).html('Save Product');
            }
        });
    });
    
    // Edit product - populate form fields
    $('.edit-product').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        
        $('#editId').val(id);
        $('#editName').val(name);
        $('#editDescription').val(description);
        $('#editPrice').val(price);
        $('#editStock').val(stock);
        
        $('#editProductModal').modal('show');
    });
    
    // Update product form submission
    $('#updateProduct').click(function() {
        if (!validateForm('editProductForm')) {
            return;
        }
        
        $.ajax({
            url: 'products.php',
            type: 'POST',
            data: $('#editProductForm').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#updateProduct').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
            },
            success: function(response) {
                if (response.success) {
                    $('#editProductModal').modal('hide');
                    showAlert(response.message);
                    // Reload page to show updated product list
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                showAlert('An error occurred while processing your request.', 'danger');
            },
            complete: function() {
                $('#updateProduct').prop('disabled', false).html('Save Changes');
            }
        });
    });
    
    // Delete product - show confirmation modal
    $('.delete-product').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        
        $('#deleteId').val(id);
        $('#deleteProductName').text(name);
        $('#deleteProductModal').modal('show');
    });
    
    // Confirm delete product
    $('#confirmDelete').click(function() {
        $.ajax({
            url: 'products.php',
            type: 'POST',
            data: $('#deleteProductForm').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#confirmDelete').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteProductModal').modal('hide');
                    showAlert(response.message);
                    // Reload page to show updated product list
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                showAlert('An error occurred while processing your request.', 'danger');
            },
            complete: function() {
                $('#confirmDelete').prop('disabled', false).html('Delete');
            }
        });
    });
    
    // Reset add product form when modal is closed
    $('#addProductModal').on('hidden.bs.modal', function() {
        $('#addProductForm')[0].reset();
        $('#addProductForm').removeClass('was-validated');
    });
    
    // Real-time search functionality
    $('#searchInput').on('input', function() {
        if ($(this).val().length >= 2 || $(this).val().length === 0) {
            $('.search-form').submit();
        }
    });
    
    // Focus on search input when page loads
    $('#searchInput').focus();
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});