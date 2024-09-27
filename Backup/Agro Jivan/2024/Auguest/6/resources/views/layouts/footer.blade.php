@if (Auth()->user() !== null && Auth()->user()->role_id == 8)
    <form id="scann_barcode_form" name="scann_barcode_form">
        <input value="" type="text" id="form_scan_order_id" name="form_scan_order_id" autofocus
            class="scanner_order_id_input">
    </form>
@endif
<div id="kt_app_footer" class="app-footer ">
    <div class="app-container  container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3 ">
        <div class="text-gray-900 order-2 order-md-1">
            <span class="text-black fw-semibold me-1"> {{ date('Y') }} &copy; Agro Jivan</span>
        </div>
    </div>
</div>
</div>
</div>
</div>

</body>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <h5 class="modal-title text-center" id="staticBackdropLabel">Break Time</h5>
            </div>
            <div class="modal-body text-center">
                <?php $break = DB::table('break_log')
                    ->where('user_id', Auth()->user() !== null ? Auth::user()->id : '')
                    ->orderBy('id', 'DESC')
                    ->first(); ?>
                @if (!blank($break))
                    <p>Break Start From:</p>
                    <p class="text-danger">
                        @if (!blank($break->break_start))
                            {{ $break->break_start }}
                        @endif
                    </p>
                    <p class="">Today's Break Time: <span class="break-time-modal text-danger"></span></p>
                @endif
                <a href="{{ route('complete_break') }}" class="btn btn-primary mx-3">Complete Break</a>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="attendance_role_user_id" id="attendance_role_user_id" value="{{Auth()->user() !== null ? Auth()->user()->role_id : ""}}">
<div class="modal fade modal-lg" id="add_scanner_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="in_out_stock_modal_header"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="div_modal_data_scanner" class="div_modal_data_scanner" method="post">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addScannerStockDetail()">Submit</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('public/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('public/assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('public/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('public/assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('public/assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('public/assets/plugins/global/fonts/font-awesome/all.min.js') }}"></script>
<script src="{{ asset('public/assets/plugins/global/fullcalendar/fullcalendar.min.js') }}"></script>
<script>
    var role_id = $('#attendance_role_user_id').val();
    var CheckAttendanceUrl = "{{ route('check_attendance') }}";
    var breakTime = "{{ route('employee-break-time-start') }}"
</script>

<script src="{{ asset('public/assets/js/global/custom.js') }}"></script>
<script src="{{ asset('public/assets/plugins/custom/date-range-picker/daterangepicker.min.js') }}"></script>
@yield('page')
@if (session('success'))
    <script>
        toastr.success('{{ session('success') }}');
    </script>
@endif
@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif
<?php $break = DB::table('break_log')->where('user_id',Auth()->user() !== null ?Auth::user()->id:"")->orderBy('id','DESC')->first(); 
    if(!empty($break)){
    if(empty($break->break_over)){
    ?>
<script>
    window.setInterval(function() {
        $.ajax({
            type: 'GET',
            url: "{{ route('break_time') }}",
            success: function(data) {
                $('.break-time-modal').html(data);
                $('.break-time').html(data);
            },
            error: function(data) {}
        });
    }, 60);
    $(document).ready(function() {
        $('#staticBackdrop').modal('show');
    });
</script>
<?php
    }
    }
    ?>
<script>
    $('#scann_barcode_form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#form_scan_order_id').val();
        $.ajax({
            method: 'get',
            url: "{{ route('detail-by-order-id') }}",
            data: {
                id: id,
            },
            success: function(res) {
                $('#in_out_stock_modal_header').html(res.order_id)
                var html = "";
                $.each(res.order_item, function(i, v) {
                    if (res.stock_detail == "") {
                        html += `<div class="row">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="product_id[]" id="product_id" value="` + v.product_detail.id + `">
                            <input type="hidden" name="scan_order_id[]" id="scan_order_id" value="` + res.id + `">
                            <input type="hidden" name="variant_id[]" id="variant_id" value="` + v.varient_detail.id + `">
                            <input type="hidden" name="in_stock[]" id="in_stock" class="form-control" value="` + v
                            .stock + `">
                            <input type="hidden" name="type" id="type" class="form-control" value="out">
                            <div class="col-md-4">Product Name:- ` + v.product_detail.product_name + `</div>
                            <div class="col-md-4">Variant Name:- ` + v.varient_detail.sku_name + `</div>
                            <div class="col-md-4">Stock:- ` + v.stock + `</div>
                            </div>`
                    } else {
                        html += `<div class="row">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="product_id[]" id="product_id" value="` + v.product_detail
                            .id + `">
                                <input type="hidden" name="scan_order_id[]" id="scan_order_id" value="` + res.id + `">
                                <input type="hidden" name="variant_id[]" id="variant_id" value="` + v.varient_detail
                            .id + `">
                                <input type="hidden" name="type" id="type" class="form-control" value="in">
                                <div class="col-md-4">Product Name:- ` + v.product_detail.product_name + `</div>
                                <div class="col-md-4">Variant Name:- ` + v.varient_detail.sku_name + `</div>
                                <div class="col-md-4">Stock:- <input type="text" name="in_stock[]" id="in_stock" class="form-control" value=""></div>
                                </div>`
                    }
                })
                $('#form_scan_order_id').focus();
                $('.div_modal_data_scanner').append(html);
                $('#add_scanner_modal').modal('show');
            },
        })
    })

    function addScannerStockDetail() {
        var formData = $('#div_modal_data_scanner').serialize();
        $.ajax({
            url: '{{ route('scan-stock-detail') }}',
            method: 'POST',
            data: formData,
            success: function(res) {
                toastr.success(res.message);
                $('#add_scanner_modal').modal('hide');
                $('#form_scan_order_id').focus();
                $('.div_modal_data_scanner')[0].reset();
            },
            error: function(xhr, status, error) {}
        });
    }
</script>
