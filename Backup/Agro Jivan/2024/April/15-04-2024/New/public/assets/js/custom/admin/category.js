$(document).ready(function (e) {
    categoryAjaxList(1);
});
function categoryValidate(){
    var name = $('#name').val();
    var categoryId = $('#parent_category_id').val();

    var cnt = 0;

    $('#name_error').html('');
    $('#parent_category_error').html('');

    if(name.trim() == ""){
        $('#name_error').html('Please enter name.');
        cnt = 1;
    }

    if(cnt == 0){
        return true;
    }
    return false;
}

function categoryAjaxList(page) {
    var search = $('#search_data').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
        },
        success: function (res) {
            $('#category_table_ajax').html('');
            $('#category_table_ajax').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    categoryAjaxList(page);
});
function deleteCategory(id) {
    var url = deleteURL.replace('id',id)
    new swal({
        title: 'Are you sure delete this Category?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Delete it!'
    }).then(function(isConfirm){
        if(isConfirm.isConfirmed){
            $.ajax({
                method:"DELETE",
                url: url,
                data:{
                    _token: token
                },
                success: function(res){
                    toastr.success(res.message);
                    categoryAjaxList(parseInt(document.querySelector('.pagination .page-item.active .page-link').innerText));
                },
                error: function(error){
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}