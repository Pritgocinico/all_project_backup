$(document).ready(function (e) {
    orderAjaxList(1);
});
function orderValidate() {
    var name = $('#name').val();
    var number = $('#phone_number').val();
    var password = $('#password').val();
    var aadharNumber = $('#aadhar_card').val();
    var panNumber = $('#pan_card').val();
    var qualification = $('#qualification').val();
    var role = $('#role').val();
    var code = $('#system_code').val();

    var cnt = 0;

    $('#name_error').html('');
    $('#phone_number_error').html('');
    $('#password_error').html('');
    $('#aadhar_card_error').html('');
    $('#pan_card_error').html('');
    $('#qualification_error').html('');
    $('#role_error').html('');

    if (name.trim() == "") {
        $('#name_error').html('Please Enter Name.');
        $('#name').focus();
        cnt = 1;
    }
    if (number.trim() == "") {
        $('#phone_number_error').html('Please Enter Phone Number.');
        cnt = 1;
    }
    var pattern = new RegExp(
        "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$"
    );
    if (password.trim() == "") {
        $('#password_error').html('Please Enter Password.');
        cnt = 1;
    } else if (!pattern.test(password)) {
        $('#password_error').html('Password allowed one uppercase, one lowercase , one number and one special character.');
        cnt = 1;
    }
    if (aadharNumber == "") {
        $('#aadhar_card_error').html('Please Select Aadhar Card.');
        cnt = 1;
    }
    if (panNumber == "") {
        $('#pan_card_error').html('Please Select Pan Card.');
        cnt = 1;
    }
    if (qualification == "") {
        $('#qualification_error').html('Please Select Qualification.');
        cnt = 1;
    }
    if (role == "") {
        $('#role_error').html('Please Select Role.');
        cnt = 1;
    }
    if (code.trim() == "") {
        $('#system_code_error').html('Please Enter System Code.');
        cnt = 1;
    }
    if (cnt == 1) {
        return false;
    }
    return true;
}

function exportCSV() {
    var format = $('#export_format').val();
    $('#export_format_error').html('');
    if(format == ""){
        $('#export_format_error').html('Please Select Export Format.')
        return false;
    }
    var role = $('#role_dropdown_export').val();
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&role=' + role + '&search=' + search, '_blank');
}
function resetForm(){
    $('#order_status').val('');
    $('#search_data').val('');
    $('#order_id').val('');
    $('#order_district').val('');
    $('#search_date').val('');
    orderAjaxList(1);
}
function orderAjaxList(page) {
    var status = $('#order_status').val();
    var search = $('#search_data').val();
    var driverId = $('#order_id').val();
    var date = $('#search_date').val();
    var order_district = $('#order_district').val();
    var userId = $('#user_id').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            status: status,
            search: search,
            driverId: driverId,
            date: date,
            order_district: order_district,
            userId: userId,
        },
        success: function (res) {
            $('#manual_order_table_ajax').html('');
            $('#manual_order_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    orderAjaxList(page);
});
function updateEmployeeValidate() {
    var name = $('#name').val();
    var number = $('#phone_number').val();
    var password = $('#password').val();
    var role = $('#role').val();
    var code = $('#system_code').val();

    var cnt = 0;

    $('#name_error').html('');
    $('#phone_number_error').html('');
    $('#password_error').html('');
    $('#aadhar_card_error').html('');
    $('#pan_card_error').html('');
    $('#qualification_error').html('');
    $('#role_error').html('');

    if (name.trim() == "") {
        $('#name_error').html('Please Enter Name.');
        $('#name').focus();
        cnt = 1;
    }
    if (number.trim() == "") {
        $('#phone_number_error').html('Please Enter Phone Number.');
        cnt = 1;
    }
    var pattern = new RegExp(
        "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[-+_!@#$%^&*.,?]).+$"
    );
    if (password.trim() !== "") {
        if (!pattern.test(password)) {
            $('#password_error').html('Password allowed one uppercase, one lowercase , one number and one special character.');
            cnt = 1;
        }
    }
    if (role == "") {
        $('#role_error').html('Please Select Role.');
        cnt = 1;
    }
    if (code.trim() == "") {
        $('#system_code_error').html('Please Enter System Code.');
        cnt = 1;
    }
    if (cnt == 1) {
        return false;
    }
    return true;
}

function cancelOrder(id) {
    var url = deleteURL
    new swal({
        title: 'Are you sure cancel this order?',
        text: "Enter Reason for cancellation",
        showCancelButton: true,
        input: 'text',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Cancel it!',
        cancelButtonText: 'Close',
        customClass: {
        validationMessage: 'my-validation-message',
          },
        preConfirm: (value) => {
            if (!value) {
              Swal.showValidationMessage('Reason for cancellation is required')
            }
          },  
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    _token: token,
                    id: id,
                    reason: isConfirm.value
                },
                success: function (res) {
                    toastr.success(res.message);
                    orderAjaxList(parseInt(document.querySelector('.pagination .page-item.active .page-link').innerText));
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}

function confirmOrder(id) {
    var url = confirmURL
    new swal({
        title: 'Are you sure confirm this order?',
        showCancelButton: true,
        confirmButtonColor: '#17c653',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Confirm it!',
        cancelButtonText: 'Close',
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    _token: token,
                    id: id,
                },
                success: function (res) {
                    toastr.success(res.message);
                    orderAjaxList(parseInt(document.querySelector('.pagination .page-item.active .page-link').innerText));
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}

const passwordField = document.getElementById("password");
const togglePassword = document.getElementById("togglePasswordEmployee");

$('#togglePasswordEmployee').on("click", function () {
    if (passwordField.type === "password") {
        passwordField.type = "text";
        togglePassword.classList.remove("fa-eye");
        togglePassword.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        togglePassword.classList.remove("fa-eye-slash");
        togglePassword.classList.add("fa-eye");
    }
});

function roleDropdown() {
    var role = $('#role').val();

    $('#department_check_div').css('display', "none");
    if (role == "2") {
        $('#department_check_div').css('display', "");
    }
}


//for display sub-district on select district.
function getSubDistrict() {
    var district = $('#district').val();
    $.ajax({
        url: disUrl,
        type: "get",
        data: {
            id: district,
        },
        dataType: 'json',
        success: function (result) {
            var html = '<option value="">Select Sub District</option>';
            $.each(result, function (i, v) {
                html += '<option value=' + v.sub_district + '>' + v.sub_district_name + '</option>'
            });

            $('#sub_district').html(html);
        }
    })
}

function getVillage() {
    var subDistrict = $('#sub_district').val();
    $.ajax({
        url: villUrl,
        type: "get",
        data: {
            id: subDistrict,
        },
        dataType: 'json',
        success: function (result) {
            var html = ' <option value="">Select Village</option>';
            $.each(result, function (i, v) {
                html += ' <option value=' + v.village_code + '>' + v.village_name + '</option>'
            });

            $('#village').html(html);
        }
    })
}
var i = 0;
$(document).on('click', '.add_product', function () {
    i = i + 1;
    $('.product_data').val(i);
    // $('.variants').removeClass('d-none');
    var html = '<input type="hidden" name="ids[]" value="var_' + i + '" />';
    html += '<div class="bg-light p-3 mt-2 product-' + i + '">';
    html += '<div class="row">';
    html += '<div class="col-md-4">';
    html += '<label for="">Category</label>';
    html += '<select name="category[]" id="" class="form-control cat cat-' + i + '" data-id="' + i + '" required>';
    html += '<option value="">Select Category...</option>';
    html += '</select>';
    html += '</div>';
    html += '<div class="col-md-6">';
    html += '<label for="Product">Product</label>';
    html += '<select name="products[]" class="form-control products products-' + i + '" data-id ="' + i + '" id="" required>';
    html += '<option value="">Select Product...</option>';
    html += '</select>';
    html += '</div>';
    html += '<div class="col-md-2 text-end">';
    html += '<a href="javascript:void(0);" class="ms-2 delete-btn delete-' + i + '" data-id="' + i + '"><img src="' + deleteImage + '" width="20px" class="me-2"></a>';
    html += '</div>';
    html += '</div>';
    html += '<div class="row mt-3">';
    html += '<div class="col-md-3">';
    html += '<label for="">Variant</label>';
    html += '<select name="variant[]" class="form-control variants-' + i + ' variant" data-id="' + i + '" id="" required>';
    html += '<option value="">Select Product Variant...</option>';
    html += '</select>';
    html += '<p class="text-danger fs-12">( Stock : <span class="stock-' + i + ' fs-12" >0</span> )</p>';
    html += '</div>';
    html += '<div class="col-md-3">';
    html += '<label for="">Price</label>';
    html += '<input type="hidden" id="item_amt-' + i + '" value="0">';
    html += '<input type="hidden" name="pr_price[]" value="" class="form-control pe-none pr-price-' + i + '">';
    html += '<input type="text" name="price[]" value="" class="form-control pe-none price-' + i + '">';
    html += '</div>';
    html += '<div class="col-md-3">';
    html += '<label for="">Quantity</label>';
    html += '<input type="number" name="quantity[]" min="0" value="1" class="form-control qty-' + i + ' qty" data-id="' + i + '">';
    html += '</div>';
    html += '<div class="col-md-1">';
    html += '<label for="Stock">Stock</label>';
    html += '<input type="number" name="stock[]" class="form-control stock-' + i + '" disabled>';
    html += '</div>';
    html += '<div class="col-md-2 m-auto text-end">';
    html += '<input type="hidden" name="product_total[]" class="pr-total-' + i + '" value="">';
    html += '<h6 class="mb-0">Total : <br><span class="text-danger"> &#x20B9; <span class="total total-' + i + '">0</span></span></h6>';
    html += '</div>';
    html += '</div>';
    html += '<div class="row product-stock-' + i + '">';
    html += '</div>';
    html += '</div>';
    $('.products_data').append(html);
    $.ajax({
        type: 'GET',
        url: categoryUrl,
        dataType: "json",
        success: function (data) {
            var html = "";
            $.each(data, function (i, v) {
                if (v.child_category_details.length > 0) {
                    html += `<optgroup label="` + v.name + `">`;
                    $.each(v.child_category_details, function (a, b) {
                        html += '<option value="' + b.id + '">' + b.name + '</option>';
                    })
                    html += '</optgroup>';
                } else {
                    html += '<option value="' + v.id + '">' + v.name + '</option>';
                }
            });
            $('.cat-' + i).append(html);
        },
        error: function (data) {
            // console.log(data);
        }
    });
});

$(document).on('change', '.cat', function (e) {
    var id = $(this).data('id');
    var cat = $(this).val();
    $.ajax({
        type: 'GET',
        url: productUrlDetail,
        data: { 'id': cat },
        success: function (data) {
            var html = "<option value=''>Select Product</option>";
            $.each(data, function (i, v) {
                html += "<option value='" + v.id + "'>" + v.product_name + "</option>";
            });
            $('.products-' + id).html('');
            $('.products-' + id).append(html);
            $('.price-' + id).val(0);
            $('.pr-price-' + id).val(0);
            $('.stock-' + id).val('');
            $('.stock-' + id).html('');
            $('.total-' + id).html(0);
            $('.pr-total-' + id).val('0')
        },
        error: function (data) {
            // console.log(data);
        }
    });
});

$(document).on('change', '.products', function () {
    var id = $(this).data('id');
    var product = $(this).val();
    $.ajax({
        type: 'GET',
        url: productVariantUrlDetail,
        data: { 'id': product },
        success: function (data) {
            var html = '<option value>Select Product Variant</option>';

            $.each(data, function (a, b) {
                html += "<option value='" + b.id + "'>" + b.sku_name + "</option>";
            });
            $('.variants-' + id).html('');
            $('.variants-' + id).append(html);
            $('.price-' + id).val(0);
            $('.pr-price-' + id).val(0);
            $('.stock-' + id).val('');
            $('.stock-' + id).html('');
            $('.product-stock-' + id).html('');
            $('.total-' + id).html(0);
            $('.pr-total-' + id).val('0')
        },
        error: function (data) {
            // console.log(data);
        }
    });
});

$(document).on('change', '.variant', function () {
    var id = $(this).data('id');
    var variant = $(this).val();
    $.ajax({
        type: 'GET',
        url: variantUrl,
        data: { 'id': variant },
        success: function (data) {
            var qty = $('.qty-' + id).val();
            $('.price-' + id).val(data.price);
            $('.pr-price-' + id).val(data.price);
            $('.stock-' + id).val(data.stock);
            $('.stock-' + id).html(data.stock);
            if (data.stock == 0) {
                $('.product-stock').html('* Stock is not available for this product.')
            }
            $('.total-' + id).html(data.price * qty);
            $('.pr-total-' + id).val(data.price * qty);
            var gtotal = $('.g-total').html();
            var amt = $('#item_amt-' + id).val();
            var ttl = parseInt(gtotal) + parseInt(data.price) - parseInt(amt);
            $('.g-total').html(ttl);
            $('.grand_total').val(ttl);
            $('#item_amt-' + id).val(data.price);
        },
        error: function (data) {
            // console.log(data);
        }
    });
});

$(document).on('change', '.qty', function () {
    var id = $(this).data('id');
    var qty = $(this).val();
    // alert(qty);
    var gtotal = $('.g-total').html();
    var amt = $('.total-' + id).html();
    // alert($('#item_amt-'+id).val()*qty);
    var amount = $('#item_amt-' + id).val() * qty;
    var ttl = parseInt(gtotal) + parseInt(amount) - parseInt(amt);
    $('.total-' + id).html(amount);
    $('.pr-total-' + id).val($('#item_amt-' + id).val() * qty);
    $('.g-total').html(ttl);
    $('.grand_total').val(ttl);
});

$(document).on('click', '.delete-btn', function () {
    var data = $(this).data('id');
    var price = $('.pr-total-' + data).val();
    var gtl = $('.g-total').html();
    $('.g-total').html(parseInt(gtl) - parseInt(price));
    var grand = $('.grand_total').val();
    $('.grand_total').val(parseInt(grand) - parseInt(price));
    // alert(price);
    $('.product-' + data).remove();
});

$(document).on('change', '#flexSwitchCheckChecked', function () {
    if ($(this).prop('checked') == true) {
        $('.lead_datetime').removeClass('d-none');
    } else {
        $('.lead_datetime').addClass('d-none');
    }
})
$(document).on('change', '#flexSwitchCheck', function () {
    if ($(this).prop('checked') == true) {
        $('.divert_order').removeClass('d-none');
    } else {
        $('.divert_order').addClass('d-none');
    }
});

function exportCSV() {
    var format = $('#export_format').val();
    var search = $('#search_data').val();
    $('#export_format_error').html('');
    if(format == ""){
        $('#export_format_error').html('Please Select Export Format.')
        return false;
    }
    window.open(exportUrl + '?format=' + format + '&search=' + search+'&type=' + type, '_blank');
}

function exportInvoice(){
    var search = $('#search_data').val();
    var date = $('#search_date').val();
    window.open(exportInvoicePDF + '?search=' + search+ '&date='+date, '_blank');
}

$(function() {
    $('.search_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
    }, function(start, end, label) {
        $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
    });
});
function resetOrderForm(){
    $('#order_status').val('');
    $('#search_data').val('');
    $('#order_id').val('');
    $('#order_district').val('');
    $('#search_date').val('');
    orderAjaxList(1)
}