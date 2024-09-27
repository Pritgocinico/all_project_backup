$(document).ready(function (e) {
    $('#search_village').select2();
    $('#search_sub_district').select2();
    leadAjaxList(1);
});


function exportCSV() {
    var format = $('#export_format').val();
    var role = $('#role_dropdown_export').val();
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&role=' + role + '&search=' + search, '_blank');
}

function resetSearch() {
    $('#search_data').val('');
    $('#order_date').val('');
    $('#lead_district').val('');
    leadAjaxList(1);
}

function leadAjaxList(page) {
    var search = $('#search_data').val();
    var date = $('#order_date').val();
    var district = $('#lead_district').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
            date: date,
            district: district,
        },
        success: function (res) {
            $('#lead_table_ajax').html('');
            $('#lead_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    leadAjaxList(page);
});

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

function getVillage(value = "", subDist = "") {
    var subDistrict = $('#sub_district').val();
    if (subDistrict == null) {
        subDistrict = subDist;
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
                if (v.village_code == value) {
                    select = "selected";
                }
                html += "<option value='" + v.village_code + "'" + select + '>' + v.village_name + '</option>';
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
    html += '<input type="number" name="stock[]" class="form-control stock-' + i + '" readonly>';
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
    window.open(exportUrl + '?format=' + format + '&search=' + search, '_blank');
}
$('#search_village').on('change', function (e) {
    searchLead('village', $(this).val());
});
$('#search_sub_district').on('change', function (e) {
    searchLead('sub_district', $(this).val());
});
$('#phoneno').on('change', function (e) {
    searchLead('phone', $(this).val());
});

function searchLead(type, value) {
    $.ajax({
        url: detailAjax,
        type: "get",
        data: {
            type: type,
            value: value,
        },
        success: function (res) {
            var srh_data = "";
            var html1 = '';
            $('#lead_call_detail').html('');
            $.each(res.data, function (i, value) {
                var html = '';
                srh_data = value;
                html += '<div class="houmanity-card card">';
                html += '<div class="card-body my-3">';
                html += '<p class=""><strong>Lead ID: </strong>' + value.lead_id + '</p>';
                html += '<span class=""><strong>Created By: </strong><span class="created"></span></span><br>';
                html += '<span class=""><strong>Customer Name: </strong>' + value.customer_name + '</span><br>';
                html += '<span class=""><strong>District Name: </strong>' + value.district_detail.district_name + '</span><br>';
                html += '<span class=""><strong>Sub District Name: </strong>' + value.sub_district_detail.sub_district_name + '</span><br>';
                html += '<span class=""><strong>Village Name: </strong>' + value.village_detail.village_name + '</span><br>';
                html += '<span class=""><strong>Mobile Number: </strong>' + value.phone_no + '</span><br>';
                html += '<p class="mt-3"><strong>Items</strong></p>';
                html += '<div class="lead-item-' + value.id + '"></div>';
                $('#lead_call_detail').append(html);
                var order_item = "";
                $.each(value.lead_detail, function (i, val) {
                    order_item += '<div class="orderItem bg-light m-2 p-2">'
                    order_item += '<span class="mb-0"><strong>Product Name: </strong>' + val.product_detail.product_name + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Variant: </strong>' + val.variant_detail.sku_name + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Quantity: </strong>' + val.quantity + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Price: </strong>&#x20B9;' + val.price + '</span><br>';
                    order_item += '</div>';
                    $('.lead-item-' + value.id).append(order_item);
                });
            });
            if (srh_data == "") {
                html1 = "<hr><p class='text-center'>No data available.</p>"
            } else {
                html1 += '<hr><p class="text-danger fs-20 text-end"><strong>Total: &#x20B9; ' + srh_data.amount + '</strong></p>';
            }
            html1 += '</div>';
            html1 += '</div>';
            $('.lead_call_detail').append(html1);
            if (type == "phone") {
                $('#customer_name').val(srh_data.customer_name);
                $('#address').val(srh_data.address);
                $('#district').val(srh_data.district);
                $('#pincode').val(srh_data.pincode);
                getSubDistrict(srh_data.sub_district);
                getVillage(srh_data.village, srh_data.sub_district);
            }
        }
    })
}
$(document).on('change', '.search-table', function () {
    $('#clear_detail_div_button').removeClass('d-none');
    $('.search-1').removeClass('d-none');
    $('.search-2').removeClass('d-none');
    var select = $(this).val();
    if (select == 1) {
        $('.search-2').addClass('d-none');
    } else if (select == 2) {
        $('.search-1').addClass('d-none');
    }
});
function exportCSV() {
    var format = $('#export_format').val();
    var cnt = 0;
    $('#export_format_error').html('')
    if (format == "") {
        $('#export_format_error').html('Please Select Export Format.');
        cnt = 1;
    }
    if (cnt == 1) {
        return false;
    }
    var date = $('#order_date').val();
    var district = $('#lead_district').val();
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&search=' + search + '&date=' + date + '&district=' + district, '_blank');
}
$(function () {
    $('.search_lead_date').daterangepicker({
        autoUpdateInput: false,
        maxDate: moment(),
    }, function (start, end, label) {
        $('.search_lead_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
    });
});

function filterSearchData() {
    $('.search-table').val("");
    $('#search_sub_district').val("").trigger("change")
    $('#search_village').val("").trigger("change")
    $('.search-1').addClass('d-none');
    $('.search-2').addClass('d-none');
    $('#clear_detail_div_button').addClass('d-none');
}

function convertOrder(id) {
    new swal({
        title: 'Are you sure lead to convert to order?',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes convert it!'
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "get",
                url: convertUrl,
                data: {
                    id: id,
                },
                success: function (res) {
                    toastr.success(res.message);
                    leadAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}