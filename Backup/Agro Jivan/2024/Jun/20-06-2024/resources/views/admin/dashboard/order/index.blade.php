@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="container-fluid ">
                <!--begin::Stats-->
                <div id="kt_app_content" class="flex-column-fluid ">
                    <div id="kt_app_content_container" class="app-container container-fluid ">
                        <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                    <input type="text" data-kt-user-table-filter="search" id="search_data" class="form-control w-250px ps-13" onkeyup="orderAjaxList(1)" placeholder="Search order" />
                                </div>
                            </div>
                            <input type="hidden" id="today_date" value="{{date('Y-m-d').'/'.date('Y-m-d')}}">
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                    <div class="parent-filter-menu">
                                        @if($status == 2)
                                        @if (Auth()->user() !== null && (Auth()->user()->role_id == '1' || Auth()->user()->role_id == '4'))
                                        <button type="button" class="btn btn-light-primary me-3" onclick="showConfirmOrderItem()">
                                            <i class="fa fa-eye "></i> View Item
                                        </button>
                                        @endif
                                        @endif
                                        <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                            <i class="ki-outline ki-filter fs-2"></i> Filter
                                        </button>
                                        <div class="menu filter-menu custom-close w-300px w-md-325px" data-kt-menu="true">
                                            <div class="px-7 py-5 d-flex align-items-center justify-content-between">
                                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                <a href="#" class="close-btn-filter"><i class="fa fa-close"></i></a>
                                            </div>
                                            <div class="separator border-gray-200"></div>
                                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                <div class="mb-10">
                                                    <label class="form-label fs-6 fw-semibold">District:</label>
                                                    <select class="form-select form-select-solid fw-bold" id="order_district" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="two-step" data-hide-search="true" onchange="getSubDistrictDetail()">
                                                        <option value="">Select District</option>
                                                        @foreach ($orderDistricts as $districts)
                                                        @if(isset($districts->districtDetail))
                                                        <option value="{{$districts->district}}">{{$districts->districtDetail->district_name}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-10">
                                                    <label class="form-label fs-6 fw-semibold">Sub District:</label>
                                                    <select class="form-select form-select-solid fw-bold" id="order_sub_district" data-placeholder="Select option" data-allow-clear="true" data-kt-user-table-filter="two-step" data-hide-search="true">
                                                        <option value="">Select Sub District</option>
                                                        @foreach ($orderSubDistricts as $subDistricts)
                                                        @if(isset($subDistricts->subDistrictDetail))
                                                        <option value="{{$subDistricts->sub_district}}">{{$subDistricts->subDistrictDetail->sub_district_name}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option" data-kt-menu-dismiss="true" onclick="resetOrderForm()" data-kt-user-table-filter="reset">Reset</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold px-6 order_filter_option" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" onclick="orderAjaxList(1)">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                                        @php $route = route('confirm-orders.create') @endphp
                                        @if(Auth()->user() == null)
                                        @php $route = route('login') @endphp
                                        @elseif(Auth()->user()->id == 1)
                                        @php $route = route('orders.create') @endphp
                                        @elseif(Auth()->user()->id == 9)
                                        @php $route = route('sale-orders.create') @endphp
                                        @endif
                                        <a href="{{ $route }}" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                            <i class="ki-outline ki-plus fs-2"></i> New Order
                                        </a>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="card-body py-4 table-responsive" id="order_table_ajax">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


</div>
</div>
</div>
</div>
<div class="modal fade" id="batch_product_detail_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batch_id_detail">Confirm Order Item List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="batch_id" id="batch_id">
                <div class="modal-body">
                    <div id="" class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Product</th>
                                    <th class="min-w-125px">Order</th>
                                    <th class="min-w-125px">Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold" id="batch_product_detail">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="generatePDF()">Download</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="confirm_role_id" value="{{Auth()->user() !== null? Auth()->user()->role_id:''}}">
</body>
@endsection
@section('page')
<script>
    var ajaxList = "{{ route('confirm-order-query-ajax') }}";
</script>
<script>
    var role = $('#confirm_role_id').val();
    var pendingItemDetail = "{{ route('confirm-order-item') }}";
    var subDistrictData = "{{route('get-sub-district-order')}}"
    var pdfUrl = "{{route('confirm-generate-confirm-order-item')}}"
    if (role == 1) {
        pendingItemDetail = "{{ route('admin-confirm-order-item') }}";
        pdfUrl = "{{route('admin-generate-confirm-order-item')}}"
    }
    $(document).ready(function(e) {
        orderAjaxList(1)
    })

    function resetOrderForm() {
        $('#search_data').val('');
        $('#order_district').val('');
        $('#order_sub_district').val('');
        orderAjaxList(1)
    }

    function orderAjaxList(page) {
        var search = $('#search_data').val();
        var district = $('#order_district').val();
        var order_sub_district = $('#order_sub_district').val();
        var status = "{{$status}}"
        $.ajax({
            method: 'get',
            url: ajaxList,
            data: {
                search: search,
                district: district,
                order_sub_district: order_sub_district,
                status: status,
                page: page,
            },
            success: function(res) {
                $('#order_table_ajax').html('');
                $('#order_table_ajax').html(res);
            }
        })
    }
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var page = $(this).attr('href').split('page=')[1];
        orderAjaxList(page)
    });

    function showConfirmOrderItem() {
        var search = $('#search_data').val();
        var district = $('#order_district').val();
        var order_sub_district = $('#order_sub_district').val();
        var date = $('#today_date').val();
        var status = "{{$status}}"
        $.ajax({
            method: "get",
            url: pendingItemDetail,
            data: {
                search: search,
                district: district,
                order_sub_district: order_sub_district,
                status: status,
                date: date,
            },
            success: function(res) {
                var html = "";
                var batchId = res.batch_id
                $('#batch_id_detail').html(res.batch_id);

                $('#batch_id').val(res.batch_id);
                $.each(res, function(i, v) {
                    html += `<tr >
                                <td><b>` + v.variant_name + `:</b></td>
                                <td>` + v.total_order + `</td>
                                <td>` + v.quantity + `</td>
                                </tr>`;
                })
                $('#batch_product_detail').html('')
                $('#batch_product_detail').html(html)
                $('#batch_product_detail_modal').modal('show')
            },
        })
    }
    function generatePDF(){
        var search = $('#search_data').val();
            var district = $('#district').val();
            var order_sub_district = $('#order_sub_district').val();
            var date = $('#today_date').val();
            var status = "{{$status}}"
            window.open(pdfUrl + '?search=' + search + "&district=" + district + "&order_sub_district=" + order_sub_district +
                "&status=" + status + "&date="+date, '_blank');
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
</script>
@endsection