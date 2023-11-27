var token = $('meta[name="csrf-token"]').attr('content');
$(document).on('change', '.status_check', function (event) {
    var thisData = $(this);
    var state = $(this).is(':checked');
    var table = $(this).data('table');
    var primary_key = $(this).data('pk');
    var field = $(this).data('field');
    var url = $(this).data('url');
    var limit = $(this).data('limit');
    var limit_field = $(this).data('limit_field');
    var limit_field_value = $(this).data('limit_field_value');
    var _token = token;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: baseUrl + 'admin' + url,
        data: {
            'state': state,
            table: table,
            primary_key: primary_key,
            _token: _token,
            field: field,
            limit: limit,
            limit_field: limit_field,
            limit_field_value: limit_field_value
        }, success: function (response) {
            if (response.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: response.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update!',
                    text: response.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
                if (state === true)
                    thisData.closest($('.status_check')).prop('checked', false);
                else
                    thisData.closest($('.status_check')).prop('checked', true);
            }
        }
    });
});

$(document).on('click', '.delete_entry', function () {
    var id = $(this).data('id');
    var url = $(this).data('url');
    if (id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-primary me-3',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                // delete the data
                $.ajax({
                    type: 'DELETE',
                    url: baseUrl + url + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function success(response) {

                        if (response.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Delete!',
                                text: response.message,
                                customClass: {
                                    confirmButton: 'btn btn-success'
                                }
                            });
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function error(_error) {
                        console.log(_error);
                    }
                });

            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: 'Cancelled',
                    text: 'The Record is not deleted!',
                    icon: 'error',
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            }
        });
    } else {
        Swal.fire('Error !', 'Entry not found', 'error');
    }
});

$(document).on('blur', '.common_sort_order', function () {
    var sort_order = $(this).val();
    var table = $(this).data('table');
    var id = $(this).data('id');
    var extra = $(this).data('extra');
    var extra_key = $(this).data('extra_key');
    var _token = token;
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: base_url + '/admin/sort_order',
        data: {sort_order: sort_order, table: table, id: id, extra: extra, extra_key: extra_key, _token: _token},
        success: function (data) {
            if (data.status == false) {
                Swal.fire('Error !', data.message, 'error');
            } else {
                Swal.fire('Success !', 'Sort order has been updated successfully', 'success');
            }
        }
    })
});
