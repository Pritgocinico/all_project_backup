$(document).ready(function(e){
    infoAjaxList(1);
});
function infoAjaxList(page) {
    var search = $('#search_data').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
        },
        success: function (res) {
            $('#hr_info_sheet_table').html('');
            $('#hr_info_sheet_table').html(res);
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    infoAjaxList(page);
});

function deleteInfoSheet(id) {
    var url = deleteUrl.replace('id', id)
    new swal({
        title: 'Are you sure delete this Info Sheet?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes Delete it!'
    }).then(function (isConfirm) {
        if (isConfirm.isConfirmed) {
            $.ajax({
                method: "post",
                url: url,
                data: {
                    _token: token
                },
                success: function (res) {
                    toastr.success(res.message);
                    infoAjaxList(1);
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}