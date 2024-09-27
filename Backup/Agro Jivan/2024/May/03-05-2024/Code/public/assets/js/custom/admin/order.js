$(document).ready(function (e) {
    $('#search_village').select2();
    $('#search_sub_district').select2();
    orderAjaxList(1);
});

$('#search_village').change(function () {
    orderDetails('village', $(this).val());
});
$('#search_sub_district').change(function () {
    orderDetails('sub_district', $(this).val());
});

$('#phoneno').change(function () {
    orderDetails('phone', $(this).val());
});

function orderDetails(type, value) {
    var type = type;
    var value = value;
    $.ajax({
        'method': 'get',
        'url': ordersList,
        data: {
            type: type,
            value: value
        },
        success: function (res) {
            var srh_data = "";

            $('#order_card').html('');
            $('.order-item').html('');
            var html1 = '';
            $.each(res.data, function (i, value) {
                var html = '';
                srh_data = value;
                var name = ""
                if (value.user_detail !== null) {
                    name = value.user_detail.name
                }
                var status = "";
                if (value.number_order.length >= 3) {
                    status = '<span class="badge badge-success fw-bold">VIP</span>';
                }
                var date = "";
                if (value.created_at !== null) {
                    date = moment(value.created_at).format('DD-MM-YYYY hh:mm:ss A')
                }
                html += '<div class="houmanity-card card">';
                html += '<div class="card-body my-3">';
                html += '<p class=""><strong>Order ID: </strong>' + value.order_id + '</p>';
                html += '<span class=""><strong>Created By: </strong>' + name + ' </span><br>';
                html += '<span class=""><strong>Created At: </strong>' + date + ' </span><br>';
                html += '<span class=""><strong>Customer Name: </strong>' + value.customer_name + '&nbsp; ' + status + '</span><br>';
                html += '<span class=""><strong>District Name: </strong>' + value.district_detail.district_name + '</span><br>';
                html += '<span class=""><strong>Sub District Name: </strong>' + value.sub_district_detail.sub_district_name + '</span><br>';
                html += '<p class="mt-3"><strong>Items</strong></p>';
                html += '<div class="order-item-' + value.id + '"></div>';
                $('#order_card').append(html);
                var order_item = "";
                $.each(value.order_item, function (i, val) {
                    var sku  = "";
                    if(val.varient_detail !== null){
                        sku = val.varient_detail.sku_name;
                    }
                    order_item += '<div class="orderItem bg-light m-2 p-2">'
                    order_item += '<span class="mb-0"><strong>Product Name: </strong>' + val.product_detail.product_name + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Variant: </strong>' + sku + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Quantity: </strong>' + val.quantity + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Price: </strong>&#x20B9;' + val.amount + '</span><br>';
                    order_item += '</div>';
                    $('.order-item-' + value.id).append(order_item);
                });
                $('#order_card').append('<hr><p class="text-danger fs-20 text-end"><strong>Total: &#x20B9; ' + value.amount + '</strong></p>');
            });
            if (srh_data == []) {
                html1 = "<hr><p class='text-center'>No data available.</p>"
            }
            html1 += '</div>';
            html1 += '</div>';
            $('#order_card').append(html1);
            if (srh_data == "") {
                $('#customer_name').val('')
                $('#address').val('')
                $('#district').val('')
                getSubDistrict('');
                $('#pincode').val("")
            }
            if (type == "phone") {
                $('#phoneno').val(value)
                $('#customer_name').val(srh_data.customer_name)
                $('#address').val(srh_data.address)
                $('#district').val(srh_data.district)
                getSubDistrict(srh_data.sub_district);
                $('#pincode').val(srh_data.pincode)
                getVillage(srh_data.sub_district,srh_data.village);
            }
        },
    });
}

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
    if (format == "") {
        $('#export_format_error').html('Please select format.');
        return false;
    }
    var role = $('#role_dropdown_export').val();
    var search = $('#search_data').val();
    var status = $('#order_status').val();
    var date = $('#search_date').val();
    var order_district = $('#order_district').val();
    var order_sub_district = $('#order_sub_district').val();
    var userId = $('#user_id').val();
    window.open(exportFile + '?format=' + format + '&role=' + role + '&search=' + search+"&status="+ status + "&date="+ date +"&date="+order_district+"&order_sub_district="+order_sub_district+"&userId="+ userId , '_blank');
}

function orderAjaxList(page) {
    var status = $('#order_status').val();
    var search = $('#search_data').val();
    var date = $('#search_date').val();
    var order_district = $('#order_district').val();
    var order_sub_district = $('#order_sub_district').val();
    var userId = $('#user_id').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            status: status,
            search: search,
            date: date,
            order_district: order_district,
            order_sub_district: order_sub_district,
            userId:userId,
        },
        success: function (res) {
            $('#order_table_ajax').html('');
            $('#order_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip();
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

function deleteEmployee(id) {
    var url = deleteURL.replace('id', id)
    new swal({
        title: 'Are you sure delete this employee?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Delete it!'
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "DELETE",
                url: url,
                data: {
                    _token: token
                },
                success: function (res) {
                    toastr.success(res.message);
                    orderAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}


//for display sub-district on select district.
function getSubDistrict(value = "") {
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
                var select = "";
                if (v.sub_district == value) {
                    select = "selected"
                }
                html += "<option value='" + v.sub_district + "'" + select + '>' + v.sub_district_name + '</option>'
            });

            $('#sub_district').html(html);
        }
    })
}

function getVillage(subDistrictCode,village) {
    var subDistrict = $('#sub_district').val();
    if(subDistrict == null){
        subDistrict = subDistrictCode;
    }
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
                var select = "";
                if(village == v.village_code){
                    select = "selected"
                }
                console.log();
                html +="<option value='" + v.village_code + "'" + select + '>' + v.village_name + '</option>'
            });

            $('#village').html(html);
        }
    })
}
var i = 1
if(parseInt(count) !== 0){
    i = parseInt(count)
}
function addProductDetail(categoryId = "") {
    i = i + 1;
    $('.product_data').val(i);
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
    html += '<a href="javascript:void(0);" class="ms-2 delete-btn delete-' + i + '" data-id="' + i + '" data-type="button"><i class="fa fa-trash fs-2"></i></a>';
    html += '</div>';
    html += '</div>';
    html += '<div class="row mt-3">';
    html += '<div class="col-md-3">';
    html += '<label for="">Variant</label>';
    html += '<select name="variant[]" class="form-control variants-' + i + ' variant" data-id="' + i + '" id="" required>';
    html += '<option value="">Select Product Variant...</option>';
    html += '</select>';
    html += '</div>';
    html += '<div class="col-md-3">';
    html += '<label for="">Price</label>';
    html += '<input type="hidden" id="item_amt-' + i + '" value="0">';
    html += '<input type="hidden" name="pr_price[]" value="" class="form-control pe-none pr-price-' + i + '">';
    html += '<input type="text" name="price[]" value="" class="form-control pe-none price-' + i + '">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<label for="">Quantity</label>';
    html += '<input type="number" name="quantity[]" min="0" value="1" class="form-control qty-' + i + ' qty" data-id="' + i + '">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<label for="Stock">Stock</label>';
    html += '<input type="number" name="stock[]" class="form-control stock-' + i + '" readonly>';
    html += '</div>';
    html += '<div class="col-md-2 m-auto text-end">';
    html += '<input type="hidden" name="product_total[]" class="pr-total-' + i + '" value="">';
    html += '<h6 class="mb-0">Total : <br><span class="text-danger"> &#x20B9; <span class="total total-' + i + '">0</span></span></h6>';
    html += '</div>';
    html += '</div>';
    html += '<div class="row product-stock-' + i + '">';
    html += '</div>';
    html += '<div class="row mt-3">';
    html += '<div class="col-md-12 m-auto text-start">';
    html += '<input type="hidden" name="free_product[]" class="free_product_' + i + '" value="">';
    html += '<input type="hidden" name="free_product_variant_id[]" class="free_product_variant_id_' + i + '" value="">';
    html += '<input type="hidden" name="code[]" class="discount_code_' + i + '" value="">';
    html += '<h6 class="mb-0">Free Product Detail: <span class="text-danger free_product-' + i + '">-</span></h6>';
    html += '<h6 class="mb-0">Free Product Variant Detail: <span class="text-danger free_variant_product-' + i + '">-</span></h6>';
    html += '</div>';
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
                    html += `<optgroup label="` + v.name + `" class="checkDetail">`;
                    $.each(v.child_category_details, function (a, b) {
                        var select = ""
                        if (b.id == categoryId) {
                            select = "selected";
                        }
                        html += `<option value="` + b.id + `"` + select + `>` + b.name + `</option>`;
                    })

                    html += `</optgroup>`;
                } else {
                    var select = ""
                    if (v.id == categoryId) {
                        select = "selected";
                    }
                    html += `<option value="` + v.id + `"` + select + `>` + v.name + `</option>`;
                }
            });
            $('.cat-' + i).append(html);
        },
        error: function (data) {
        }
    });
}

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
            $('.qty-' + id).attr('max', data.stock)
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
    var itemId = $(this).data('id');
    var type = $(this).data('type');
    
    if (type == "button") {
        $('.var-' + itemId).remove();
        var price = $('.pr-total-' + itemId).val();
        var gtl = $('.g-total').html();
        $('.g-total').html(parseInt(gtl) - price);
        var grand = $('.grand_total').val();
        $('.grand_total').val(parseInt(grand) - parseInt(price));
        $('.product-' + itemId).remove();
        return true;
    }
    Swal.fire({
        title: 'Are you sure delete this order item?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : deleteOrderItem,
                type : 'GET',
                dataType:'json',
                data:{
                    id:itemId,
                },
                success : function(data) {
                    $('.var-'+itemId).remove();
                    var price = $('.pr-total-' + itemId).val();
                    var gtl = $('.g-total').html();
                    $('.g-total').html(parseInt(gtl) - price);
                    var grand = $('.grand_total').val();
                    $('.grand_total').val(parseInt(grand) - parseInt(price));
                    $('.product-' + itemId).remove();
                }
            });
        }
    });
    
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
    $('#export_format_error').html('');
    if (format == "") {
        $('#export_format_error').html('Please select format.');
        return false;
    }
    var user_id = $('#user_id').val();
    var search = $('#search_data').val();
    var order_sub_district = $('#order_sub_district').val();
    var order_district = $('#order_district').val();
    var date = $('#search_date').val();
    window.open(exportUrl + '?format=' + format + '&userId=' + user_id + '&search=' + search+"&order_sub_district="+order_sub_district+'&order_district='+order_district+"&date="+date, '_blank');
}
function filterSearchData() {
    $('.search-table').val("");
    $('#order_admin_create_div').addClass('d-none');
    $('#search_sub_district').val("").trigger("change")
    $('#search_village').val("").trigger("change")
    $('.search-1').addClass('d-none');
    $('.search-2').addClass('d-none');
}
$(document).on('change', '.search-table', function () {
    $('#order_admin_create_div').removeClass('d-none');
    $('.search-1').removeClass('d-none');
    $('.search-2').removeClass('d-none');
    var select = $(this).val();
    if (select == 1) {
        $('.search-2').addClass('d-none');
    } else if (select == 2) {
        $('.search-1').addClass('d-none');
    }
});

function resetOrderForm() {
    $('#order_status').val('');
    $('#search_data').val('');
    $('#order_id').val('');
    $('#order_district').val('');
    $('#order_sub_district').val('');
    $('#search_date').val('');
    orderAjaxList(1)
}

$(function () {
    $('.search_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
    }, function (start, end, label) {
        $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
    });
});
$('#scheme_code').on('change', function (e) {
    var schemeCode = $(this).val();
    if (schemeCode !== "") {
        $.ajax({
            url: schemeCodeDetail,
            method: 'get',
            data: {
                code: schemeCode,
            }, success: function (res) {
                $('#scheme_product_div').addClass('d-none');
                if (res.discount_item_detail.length == 1) {
                    var itemDetail = res.discount_item_detail[0];
                    addProductDetail(itemDetail.product_detail.get_product_detail.category_id);
                    getProductData(itemDetail.product_detail.get_product_detail.category_id, itemDetail.product_id);
                    getProductVariantDetail(itemDetail.product_detail.product_id, itemDetail.product_id)
                    getVariantStockDetail(itemDetail.product_id);
                    $('.free_variant_product-' + i).html(itemDetail.free_product_detail.get_product_detail.product_name)
                    $('.free_product-' + i).html(itemDetail.free_product_detail.sku_name)
                    $('.free_product_variant_id_' + i).val(itemDetail.free_product_detail.id);
                    $('.free_product_' + i).val(itemDetail.free_product_detail.product_id);
                } else {
                    var html = `<option value="">Select Scheme Product</option>`;
                    $('#scheme_product_div').removeClass('d-none');
                    $.each(res.discount_item_detail, function (i, v) {
                        html += `<option value="` + v.product_id + `">` + v.product_detail.sku_name + `</option>`
                    })
                    $('#scheme_product_id').html('');
                    $('#scheme_product_id').html(html);
                }
                $('.discount_code_'+i).val(schemeCode);
            }
        })
    }
})

function getProductData(id, productId) {
    $.ajax({
        type: 'GET',
        url: productUrlDetail,
        data: { 'id': id },
        success: function (data) {
            var html = "<option value=''>Select Product</option>";
            $.each(data, function (i, v) {
                var select = "";
                if (v.id == productId) {
                    select = "selected";
                }
                html += "<option value='" + v.id + "'" + select + ">" + v.product_name + "</option>";
            });
            $('.products-' + i).html('');
            $('.products-' + i).append(html);
            $('.price-' + i).val(0);
            $('.pr-price-' + i).val(0);
            $('.stock-' + i).val('');
            $('.stock-' + i).html('');
            $('.total-' + i).html(0);
            $('.pr-total-' + i).val('0')
        },
    });
}

function getProductVariantDetail(id, variantID) {
    $.ajax({
        type: 'GET',
        url: productVariantUrlDetail,
        data: { 'id': id },
        success: function (data) {
            var html = '<option value>Select Product Variant</option>';
            $.each(data, function (a, b) {
                var select = "";
                if (b.id == variantID) {
                    select = "selected";
                }
                html += "<option value='" + b.id + "'" + select + ">" + b.sku_name + "</option>";
            });

            $('.variants-' + i).html('');
            $('.variants-' + i).append(html);
            $('.price-' + i).val(0);
            $('.pr-price-' + i).val(0);
            $('.stock-' + i).val('');
            $('.stock-' + i).html('');
            $('.product-stock-' + i).html('');
            $('.total-' + i).html(0);
            $('.pr-total-' + i).val('0')
        },
    });
}

function getVariantStockDetail(productId) {
    $.ajax({
        type: 'GET',
        url: variantUrl,
        data: { 'id': productId },
        success: function (data) {
            var qty = $('.qty-' + i).val();
            $('.qty-' + i).attr('max', data.stock)
            $('.price-' + i).val(data.price);
            $('.pr-price-' + i).val(data.price);
            $('.stock-' + i).val(data.stock);
            $('.stock-' + i).html(data.stock);
            if (data.stock == 0) {
                $('.product-stock').html('* Stock is not available for this product.')
            }
            $('.total-' + i).html(data.price * qty);
            $('.pr-total-' + i).val(data.price * qty);
            var gtotal = $('.g-total').html();
            var amt = $('#item_amt-' + i).val();
            var ttl = parseInt(gtotal) + parseInt(data.price) - parseInt(amt);
            $('.g-total').html(ttl);
            $('.grand_total').val(ttl);
            $('#item_amt-' + i).val(data.price);
        },
    });
}
$('#scheme_product_id').on('change', function (e) {
    var productId = $(this).val();
    var schemeCode = $('.scheme_code').val();
    
    if (schemeCode !== "") {
        $.ajax({
            url: schemeCodeProduct,
            method: 'get',
            data: {
                code: schemeCode,
                productId: productId,
            }, success: function (res) {
                addProductDetail(res.product_detail.get_product_detail.category_id);
                getProductData(res.product_detail.get_product_detail.category_id, res.product_id);
                getProductVariantDetail(res.product_detail.product_id, res.product_id)
                getVariantStockDetail(res.product_id);
                $('.free_variant_product-' + i).html(res.free_product_detail.get_product_detail.product_name)
                $('.free_product-' + i).html(res.free_product_detail.sku_name)
                $('.free_product_variant_id_' + i).val(res.free_product_id);
                $('.free_product_' + i).val(res.free_product_detail.product_id);
            }
        })
        $('.discount_code_'+i).val(schemeCode);
    }
})

function setLoader(){
    document.getElementById("create_order_button").innerHTML ='<i class="fa fa-spinner fa-spin"></i>';
}

function getSubDistrictDetail(){
    var district = $('#order_district').val()
    $.ajax({
        method:"get",
        url:subDistrictData,
        data:{
            district:district
        },success:function(res){
            var html = "<option value=''>Select Sub District</option>";
            $.each(res,function(i,v){
                html += "<option value='"+v.sub_district+"'>"+v.sub_district_detail.sub_district_name+"</option>"
            })
            $('#order_sub_district').html('');
            $('#order_sub_district').html(html);
        }
    })
}