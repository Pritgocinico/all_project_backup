@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                                <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" placeholder="Search"
                                                onkeyup="orderAjaxList(1)" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                            <div class="parent-filter-menu">
                                                <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                    <i class="ki-outline ki-filter fs-2"></i> Filter
                                                </button>
                                                <div class="menu filter-menu w-300px w-md-325px"
                                                data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                        <input type="text" name="batch_date" id="batch_date"
                                                            class="form-control form-select-solid fw-bold search_batch_date"
                                                            max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetOrderForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit" class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" 
                                                            onclick="orderAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <div class="card-body py-4 table-responsive" id="batch_data_table"></div>
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

    </body>
    <div class="modal fade modal-lg" id="add_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">In Out Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="div_modal_data" class="div_modal_data" method="post">
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addStockDetail()">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page')
    <script>
        $(function() {
            $('.search_batch_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.search_batch_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
            });
        });
        $(document).ready(function(e) {
            orderAjaxList(1)
        });

        function orderAjaxList(page) {
            $.ajax({
                method:'get',
                url: "{{route('in-out-stock-ajax')}}",
                data:{
                    page:page,
                    search:$('#search_data').val(),
                    batch_date:$('#batch_date').val(),
                },
                success: function(res){
                    $('#batch_data_table').html('')
                    $('#batch_data_table').html(res)
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            })
        }
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            orderAjaxList(page);
        });
        function getOrderDetail(id,type){
            $.ajax({
                method:'get',
                url:"{{route('order-by-id')}}",
                data:{
                    id:id,
                },
                success:function(res){
                    $('#exampleModalLabel').html(res.order_id)
                    var html = "";

                    $.each(res.order_item,function(i,v){
                        html += `<div class="row">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="product_id[]" id="product_id" value="`+v.product_detail.id+`">
                            <input type="hidden" name="order_id" id="order_id" value="`+res.id+`">
                            <input type="hidden" name="variant_id[]" id="variant_id" value="`+v.varient_detail.id+`">
                            <input type="hidden" name="type" id="type" value="`+type+`">
                                <div class="col-md-4">Product Name:- `+v.product_detail.product_name+`</div>
                                <div class="col-md-4">Variant Name:- `+v.varient_detail.sku_name+`</div>
                                <div class="col-md-4">`+type+` Stock:- <input type="text" name="in_stock[]" id="in_stock" class="form-control"></div>
                            </div>`
                    })
                    $('.div_modal_data').html(html);
                    $('#add_department_modal').modal('show');
                },
            })
        }
        function addStockDetail(){
            var formData = $('#div_modal_data').serialize();
            $.ajax({
                url: '{{route("store-stock-detail")}}',
                method: 'POST',
                data: formData,
                
                success: function(res) {
                    toastr.success(res.message);
                    orderAjaxList(1)
                    $('#add_department_modal').modal('hide');
                },
                error: function(xhr, status, error) {
                }
            });
        }
        function resetOrderForm(){
            $('#search_data').val('')
            $('#batch_date').val('')
            orderAjaxList(1)
        }
    </script>
@endsection
