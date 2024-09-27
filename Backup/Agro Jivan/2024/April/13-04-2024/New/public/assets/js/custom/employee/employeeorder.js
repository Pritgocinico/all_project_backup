$(document).ready(function (e) {
    $('#search_village').select2();
    $('#search_sub_district').select2();
    employeeOrderAjaxList(1);
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
    var srh_data1 = "";
    var srh_data = "";
    var cnt = 0;
    $.ajax({
        'method': 'get',
        'url': ordersList,
        data: {
            type: type,
            value: value
        },
        success: function (res) {
            $('#order_card').html('')
            var html1 = '';
            $.each(res.data, function (i, value) {
                cnt = 1;
                var html = '';
                var name = "-";
                if (value.user_detail !== null) {
                    name = value.user_detail.name.charAt(0).toUpperCase() + value.user_detail.name.slice(1)
                }
                var date = "";
                if (value.created_at !== null) {
                    date = moment(value.created_a).format('DD-MM-YYYY h:mm:ss A');
                }
                var htmlTotal = ""
                srh_data = value;
                html += '<div class="houmanity-card card">';
                html += '<div class="card-body my-3">';
                html += '<p class="text-uppercase"><strong>Order ID: </strong>' + value.order_id + '</p>';
                html += '<span class=""><strong>Created By: </strong>' + name + '</span><br>';
                html += '<span class=""><strong>Created At: </strong>' + date + '</span><br>';
                html += '<span class=""><strong>Customer Name: </strong>' + value.customer_name + '</span><br>';
                html += '<span class=""><strong>District Name: </strong>' + value.district_detail.district_name + '</span><br>';
                html += '<span class=""><strong>Sub District Name: </strong>' + value.sub_district_detail.sub_district_name + '</span><br>';
                html += '<span class=""><strong>Village Name: </strong>' + value.village_detail.village_name + '</span><br>';
                html += '<span class=""><strong>Mobile Number: </strong>' + value.phoneno + '</span><br>';
                html += '<p class="mt-3"><strong>Items</strong></p>';
                html += '<div class="order-item-' + value.id + '"></div>';
                $('#order_card').append(html);
                var order_item = "";
                $.each(value.order_item, function (i, val) {
                    order_item += '<div class="orderItem bg-light m-2 p-2">'
                    order_item += '<span class="mb-0"><strong>Product Name: </strong>' + val.product_detail.product_name + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Variant: </strong>' + val.varient_detail.sku_name + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Quantity: </strong>' + val.quantity + '</span><br>';
                    order_item += '<span class="mb-0"><strong>Price: </strong>&#x20B9;' + val.price + '</span><br>';
                    order_item += '</div>';
                    $('.order-item-' + value.id).append(order_item);
                });
                htmlTotal = '<hr><p class="text-danger fs-20 text-end"><strong>Total: &#x20B9; ' + srh_data.amount + '</strong></p>';
                $('#order_card').append(htmlTotal);
            });
            
            html1 += '</div>';
            html1 += '</div>';
            $('#order_card').append(html1);
            if (type == "phone") {
                $('#customer_name').val(srh_data.customer_name);
                $('#address').val(srh_data.address);
                $('#district').val(srh_data.district);
                $('#pincode').val(srh_data.pincode);
                getSubDistrict(srh_data.sub_district);
                getVillage(srh_data.village, srh_data.sub_district);
            }
        },
    });
    $.ajax({
        url: detailAjax,
        type: "get",
        data: {
            type: type,
            value: value,
        },
        success: function (res) {
            var html1 = '';
            $.each(res.data, function (i, value) {
                cnt = 1;
                var html = '';
                var htmlTotal = '';
                srh_data1 = value;
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
                $('#order_card').append(html);
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
                htmlTotal = '<hr><p class="text-danger fs-20 text-end"><strong>Total: &#x20B9; ' + srh_data1.amount + '</strong></p>';
                $('#order_card').append(htmlTotal);
            });
            html1 += '</div>';
            html1 += '</div>';
            if (cnt == 0){
                html1 += "<hr><p class='text-center'>No data available.</p>";
            }
            $('#order_card').append(html1);
            console.log(cnt);
            
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
function employeeOrderAjaxList(page) {
    var search = $('#search_data').val();
    var status = $('#order_status').val();
    var district = $('#order_district').val();
    var date = $('#order_date').val();
    $.ajax({
        'method': 'get',
        'url': OrderajaxList,
        data: {
            page: page,
            search: search,
            status: status,
            district: district,
            date: date,
        },
        success: function (res) {
            $('#employee_order_table_ajax').html('');
            $('#employee_order_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip()
        },
    });
}

//for display sub-district on select district.
function getSubDistrict(subDistrict = "") {
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
                if (v.sub_district == subDistrict) {
                    select = "selected"
                }
                html += '<option value="' + v.sub_district + '"' + select + '>' + v.sub_district_name + '</option>'
            });

            $('#sub_district').html(html);
        }
    })
}

function getVillage(village = "", sub_district = "") {
    var subDistrict = $('#sub_district').val();
    if (subDistrict == null) {
        subDistrict = sub_district;
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
                if (v.village_code == village) {
                    select = "selected"
                }
                html += ' <option value="' + v.village_code + '"' + select + '>' + v.village_name + '</option>'
            });

            $('#village').html(html);
        }
    })
}

var i = 0;
function addProductDetail(categoryId = "") {
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
                    html += `<optgroup label="` + v.name + `">`;
                    $.each(v.child_category_details, function (a, b) {
                        html += `<option value="` + b.id + `">` + b.name + `</option>`;
                        var select = ""
                        if (b.id == categoryId) {
                            select = "selected";
                        }
                        html += `<option value="` + b.id + `"` + select + `>` + b.name + `</option>`;
                    })
                    html += '</optgroup>';
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
            // console.log(data);
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
    $('.g-total').html(parseInt(gtl) - price);
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

$('#create_order_button').click('submit', function (event) {
    var form = $("#create_order_form").serializeArray();
    var html = "";
    var table = "";
    var status = $('#flexSwitchCheckChecked').val();
    if(status  == "on"){
        $('#exampleModalLabel').html('Confirm Lead');
    }
    $.each(form, function (i, v) {
        var district = v['value'];
        var symbol  = "";
        if (v['name'] == "district") {
            district = "";
            if ($('#district').val() != "") {
                district = $('#district option:selected').text();
            }
        }
        if (v['name'] == "state") {
            district = $('#state_id option:selected').text();
        }
        if (v['name'] == "sub_district") {
            district = $('#sub_district option:selected').text();
        }
        if (v['name'] == "village") {
            district = $('#village option:selected').text();
        }
        var option = v['name'];
        if (v['name'] == "phoneno") {
            option = "Phone No."
        }
        if (v['name'] == "customer_name") {
            option = "Customer Name"
        }
        if (v['name'] == "sub_district") {
            option = "Sub District"
        }
        if (v['name'] == "amount") {
            symbol = "₹"
        }
        if (v['name'] == "excepted_delievery_date") {
            option = "Excepted Delievery Date"
        }
        if (v['name'] != '_token' && v['name'] != 'lead_datetime' && v['name'] != 'product_data' && v['name'] != 'divert_to' && v['name'] != 'divert_note' && !v['name'].includes("ids") && !v['name'].includes("category") && !v['name'].includes("products") && !v['name'].includes("variant") && !v['name'].includes("pr_price") && !v['name'].includes("price") && !v['name'].includes("quantity") && !v['name'].includes("product_total") && !v['name'].includes("product_data") && !v['name'].includes("search_sub_district") && !v['name'].includes("search_village") && !v['name'].includes("stock")) {
            html += `<div class="col-md-4 fw-bold p-3">` + option.toUpperCase() + `: </div>
                <div class="col-md-8 p-3">`+symbol + district + `</div>
                <hr>`;
        }
        if (v['name'] == 'product_data') {
            for (let step = 1; step <= v['value']; step++) {
                table += `<tr>
                        <td>`+ $('.products-' + step + ' option:selected').text() + `</td>
                        <td>`+ $('.cat-' + step + ' option:selected').text() + `</td>
                        <td>`+ $('.variants-' + step + ' option:selected').text() + `</td>
                        <td>`+ $('.price-' + step).val() + `</td>
                        <td>`+ $('.qty-' + step).val() + `</td>
                        <td>₹`+ $('.total-' + step).html() + `</td>
                    </tr>`;

            }
        }
        $('#total_cal_div').html('<h6 class="text-danger" id="all_product_total">₹' + $('.g-total').html() + '</h6>')
    })
    $('#confirm_order_detail').html("")
    $('#confirm_order_detail').html(html)

    $('#confirm_order_detail_table').html("")
    $('#confirm_order_detail_table').html(table)
    $('#edit_ticket_modal').modal('show');
});

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

function resetSearch() {
    $('#search_data').val('');
    $('#order_status').val('');
    $('#order_district').val('');
    $('#order_date').val('');
    employeeOrderAjaxList(1);
}
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
    var search = $('#search_data').val();
    var status = $('#order_status').val();
    var district = $('#order_district').val();
    var date = $('#order_date').val();
    window.open(exportFile + '?format=' + format + '&search=' + search + '&status=' + status + '&district=' + district + '&date=' + date + '&type=all', '_blank');
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    employeeOrderAjaxList(page);
});

function filterSearchData() {
    $('.search-table').val("");
    $('#search_sub_district').val("").trigger("change")
    $('#search_village').val("").trigger("change")
    $('.search-1').addClass('d-none');
    $('.search-2').addClass('d-none');
    $('#clear_detail_div_button').addClass('d-none');
}

$('#scheme_code').on('change', function (e) {
    var schemeCode = $(this).val();
    $('.order_lead_checkbox').removeClass('d-none')
    if (schemeCode !== "") {
        $('.order_lead_checkbox').addClass('d-none')
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
    document.getElementById("confirm_order_submit_button").innerHTML ='<i class="fa fa-spinner fa-spin"></i>';
}