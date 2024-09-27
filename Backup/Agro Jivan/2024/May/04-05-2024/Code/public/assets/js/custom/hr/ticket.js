$(document).ready(function(e){
    ticketAjaxList(1);
});
function ticketAjaxList(page) {
    var search = $('#search_data').val();
    var userId = $('#ticket_dropdown_filter').val();
    var dateFilter = $('#ticket_date').val();    

    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            page: page,
            search: search,
            userId: userId,
            dateFilter: dateFilter,
        },
        success: function (res) {
            $('#hr_ticket_ajax_table').html('');
            $('#hr_ticket_ajax_table').html(res);
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    ticketAjaxList(page);
});

// search_ticket_date
$(function () {
    $('.search_ticket_date').daterangepicker({
        autoUpdateInput: false,
    }, function (start, end, label) {
        $('.search_ticket_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
        ticketAjaxList(1)
    });
});

function exportCSVTicket() {
    var format = $('#export_format').val();
    var search = $('#search_data').val();
    var userId = $('#ticket_dropdown_filter').val();
    var dateFilter = $('#ticket_date').val(); 
    window.open(exportFileTicket + '?format=' + format + '&userId=' + userId + '&dateFilter=' + dateFilter + '&search=' + search, '_blank');
}