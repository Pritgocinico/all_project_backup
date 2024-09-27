$(document).ready(function (e) {
    schemeAjaxList(1);
});

function schemeAjaxList(page) {
    var search = $('#search_data').val();
    $.ajax({
        method: 'get',
        url: ajax,
        data: {
            page: page,
            search: search,
        },
        success: function (res) {
            $('#scheme_table_ajax').html('')
            $('#scheme_table_ajax').html(res)
            $('[data-bs-toggle="tooltip"]').tooltip()
        },
    })
}

function deleteScheme(id) {
    var url = deleteUrl.replace('id', id)
    new swal({
        title: 'Are you sure delete this Scheme?',
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
                                schemeAjaxList(parseInt(textNumber.innerText));
                            } else {
                                schemeAjaxList(1);
                            }
                },
                error: function (error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    schemeAjaxList(page);
});