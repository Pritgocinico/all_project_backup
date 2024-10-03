var i = 0;
$(document).on('click', '.add_variant', function () {
    i = i + 1;
    $('.variants').removeClass('d-none');
                                        
    var html = '';
    html += '<div class="row mt-2 var-' + i + '">';
    html += '<div class="col-md-3">';
    html += '<input type="text" class="form-control" name="sku_name[]" placeholder="Variant SKU">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="text" class="form-control" name="capacity[]" placeholder="Variant Capacity">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="number" class="form-control variant_price" data-id="' + i + '" name="price[]" placeholder="Variant Price">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="text" class="form-control without_tax_'+i+'" name="price_without_tax[]" placeholder="Variant Rate" readonly>';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="number" class="form-control" name="stock[]" value = "0" placeholder="Variant Stock">';
    html += '</div>';
    html += '<div class="col-md-1">';
    html += '<a href="javascript:void(0);" class="delete-variant" data-id="' + i + '"><img src="' + image + '" width="20px" class="me-2"></a>';
    html += '</div>';
    html += '</div>';
    $('.variant').append(html);
});
$(document).on('click', '.delete-variant', function () {
    var var_id = $(this).data('id');
    $('.var-' + var_id).remove();
});

function productValidate() {
    var name = $('#name').val();
    var sku = $('#sku').val();
    var categoryId = $('#category_id').val();
    var image = $('#product_image').val();
    var c_gst = $('#c_gst').val();
    var s_gst = $('#s_gst').val();
    var product_type = $('#product_type').val();

    var cnt = 0;
    $('#name_error').html("");
    $('#sku_error').html("");
    $('#category_id_error').html("");
    $('#product_image_error').html("");
    $('#c_gst_error').html("");
    $('#s_gst_error').html("");
    $('#product_type_error').html("");

    if (name.trim() == "") {
        $('#name_error').html("Please Enter Name.");
        cnt = 1;
    }
    if (sku.trim() == "") {
        $('#sku_error').html("Please Enter sku.");
        cnt = 1;
    }
    if (categoryId.trim() == "") {
        $('#category_id_error').html("Please Select Category Id.");
        cnt = 1;
    }
    if (image.trim() == "") {
        $('#product_image_error').html("Please Select Product Image.");
        cnt = 1;
    }
    if(product_type == "tax_product"){
        
        if (c_gst.trim() == "") {
            $('#c_gst_error').html("Please Enter c gst.");
            cnt = 1;
        }
        if (s_gst.trim() == "") {
            $('#s_gst_error').html("Please Enter s gst.");
            cnt = 1;
        }
    }
    if (i == 0) {
        $('#variant_error').html("Please Enter Product Variant");
        cnt = 1;
    }
    if(product_type == ""){
        $('#product_type_error').html("Please Select Product Type");
        cnt = 1;
    }
    if(product_type !== ""){
        if(product_type == "tax_product"){
            if(c_gst.trim() == ""){
                $('#c_gst_error').html("Please enter c gst.");
                cnt =1;
            }
            if(s_gst.trim() == ""){
                $('#s_gst_error').html("Please enter s gst.");
                cnt =1;
            }
        }
    }

    if (cnt == 1) {
        return false;
    }
    return true;
}
$(document).ready(function (e) {
    productAjaxList(1);
});
function productAjaxList(page) {
    var status = $('#product_status').val();
    var search = $('#search_data').val();
    var categoryId = $('#category_id').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            status: status,
            search: search,
            categoryId: categoryId,
        },
        success: function (res) {
            $('#product_table_ajax').html('');
            $('#product_table_ajax').html(res);

        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    productAjaxList(page);
});

function exportCSV() {
    var format = $('#export_format').val();
    var search = $('#search_data').val();
    window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
}

function productUpdateValidate() {
    var name = $('#name').val();
    var sku = $('#sku').val();
    var categoryId = $('#category_id').val();
    var c_gst = $('#c_gst').val();
    var s_gst = $('#s_gst').val();
     var product_type = $('#product_type').val();

    var cnt = 0;
    $('#name_error').html("");
    $('#sku_error').html("");
    $('#category_id_error').html("");
    $('#c_gst_error').html("");
    $('#s_gst_error').html("");

    if (name.trim() == "") {
        $('#name_error').html("Please Enter Name.");
        cnt = 1;
    }
    if (sku.trim() == "") {
        $('#sku_error').html("Please Enter sku.");
        cnt = 1;
    }
    if (categoryId.trim() == "") {
        $('#category_id_error').html("Please Select Category Id.");
        cnt = 1;
    }

    if(product_type !== ""){
        if(product_type == "tax_product"){
            if(c_gst.trim() == ""){
                $('#c_gst_error').html("Please enter c gst.");
                cnt =1;
            }
            if(s_gst.trim() == ""){
                $('#s_gst_error').html("Please enter s gst.");
                cnt =1;
            }
        }
    }


    if (cnt == 1) {
        return false;
    }
    return true;
}

$(document).on('click', '.delete-variant-edit', function () {
    var var_id = $(this).data('id');
    var type = $(this).data('type');
    if (type == "button") {
        $('.var-' + var_id).remove();
        return true;
    }
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url : variantDelete,
                type : 'GET',
                dataType:'json',
                data:{
                    id:var_id,
                },
                success : function(data) {
                    $('.var-'+var_id).remove();
                }
            });
        }
    });
});
$(document).on('click', '.add_variant_edit', function () {
    i = i + 1;
    $('.variants').removeClass('d-none');
    var html = '<input type="hidden" name="ids[]" value="var_'+i+'" />';
    html += '<div class="row mt-2 var-' + i + '">';
    html += '<div class="col-md-3">';
    html += '<input type="text" class="form-control" name="sku_name[]" placeholder="Variant SKU">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="text" class="form-control" name="capacity[]" placeholder="Variant Capacity">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="number" class="form-control variant_price" data-id="' + i + '" name="price[]" placeholder="Variant Price">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="text" class="form-control without_tax_'+i+'" name="price_without_tax[]" placeholder="Variant Rate">';
    html += '</div>';
    html += '<div class="col-md-2">';
    html += '<input type="number" class="form-control" name="stock[]" value = "0" placeholder="Variant Stock">';
    html += '</div>';
    html += '<div class="col-md-1">';
    html += '<a href="javascript:void(0);" class="delete-variant-edit" data-type="button" data-id="' + i + '"><img src="' + image + '" width="20px" class="me-2"></a>';
    html += '</div>';
    html += '</div>';
    $('.variant').append(html);
});

function deleteProduct(id) {
    var url = deleteURL.replace('id', id)
    new swal({
        title: 'Are you sure delete this Product?',
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
                    var textNumber = document.querySelector('.pagination .page-item.active .page-link');
                            if (textNumber !== null) {
                                productAjaxList(parseInt(textNumber.innerText));
                            } else {
                                productAjaxList(1);
                            }
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}

$(document).on('change', '.variant_price', function () {
    var product_type = $('#product_type').val();
    var c_gst = $('#c_gst').val();
    var s_gst = $('#s_gst').val();
    $('#product_type_error').html("");
    $('#c_gst_error').html("");
    $('#s_gst_error').html("");
    var var_id = $(this).data('id');
    var value = $(this).val();
    // without_tax_1
    if(product_type == ""){
        $('#product_type_error').html("Please Select Product Type");
        $('#product_type').focus();
        cnt = 1;
    }
    if(product_type !== ""){
        if(product_type == "tax_product"){
            if(c_gst.trim() == ""){
                $('#c_gst_error').html("Please enter c gst.");
                $('#c_gst').focus();
                cnt =1;
            }
            if(s_gst.trim() == ""){
                $('#s_gst_error').html("Please enter s gst.");
                $('#s_gst').focus();
                cnt =1;
            }
        }
    }
    
    if(product_type == "tax_product"){
        var gstPrice = ((parseInt(c_gst)+parseInt(s_gst))/100) + 1;
        var priceAmount = value / gstPrice;
        $('.without_tax_'+var_id).val(priceAmount.toFixed(2))
        console.log(priceAmount.toFixed(2));
    } else {
    $('.without_tax_'+var_id).val(value)
    }
    // var total = parseInt(c_gst)+parseInt(s_gst);
    // if(c_gst == "" && s_gst == ""){
    //     $('.without_tax_'+var_id).val(value)
    // } else {
    //     var tax = value*total /100;
    //     var without_tax_price = value - tax;
    //     $('.without_tax_'+var_id).val(without_tax_price)
    // }
});

function generateSKUName(){
    var name = $('#name').val();
    var number = String(lastId).padStart(5, '0');
    var string = 'AGRJVN-'+name.substring(0,3).toUpperCase()+'-' + number;
    $('#sku').val(string);
}
function resetForm(){
    $('#product_status').val('');
    $('#search_data').val('');
    productAjaxList(1)
}