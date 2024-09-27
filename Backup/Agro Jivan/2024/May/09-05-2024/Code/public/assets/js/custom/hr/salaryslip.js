$(document).ready(function (e) {
    // alert('ok');
    salaryslipAjax(1);
});

function addSalaryslip() {
    var month = $('#month').val();
    var emp_id = $('#emp_id').val();
    var working_days = $('#working_days').val();
    var present_days = $('#present_days').val();
    var leaves = $('#leaves').val();
    var pt = $('#pt').val();
    var payable_salary = $('#payable_salary').val();

    $.ajax({
        'method': 'post',
        'url': storeURL,
        data: {
            month: month,
            emp_id: emp_id,
            working_days: working_days,
            present_days: present_days,
            leaves: leaves,
            pt: pt,
            payable_salary: payable_salary,
            _token: token,
        }, success: function (res) {
            toastr.success(res.message);
            $("#generate_salaryslip").modal('hide');
            salaryslipAjax(1)
        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}

function calculateEmployeeSalary(){
    var id =$('#emp_id').val();
    var month = $('#month').val();
    $.ajax({
        'method': 'get',
        'url': getSalaryDetail,
        data: {
            id:id,
            month:month
        }, success: function (res) {
            $('#working_days').val(res.data.totalDay);
            var day = res.data.totalHalfDay/2;
            $('#present_days').val(res.data.totalPresentDay+day);
            $('#leaves').val(res.data.totalLeave + day);
            $('#total_payable_salary').val(res.data.paySalary.toFixed(2));
        }, error: function (error) {
            toastr.error(error.responseJSON.message)
        }
    });
}
function salaryslipAjax(page){
    var search = $('#search_data').val();
    $.ajax({
        'method': 'get',
        'url': ajaxList,
        data: {
            search:search,
            page:page
        }, success: function (res) {
            $('#salaryslip_table_ajax').html("");
            $('#salaryslip_table_ajax').html(res);
        },
    });
}
$(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    salaryslipAjax(page);
});

function generateSalarySlip(salaryId,userId){
    window.open(salaryDetail + '?salaryId=' + salaryId+'&userId='+userId , '_blank');
}