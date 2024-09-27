@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="mb-4 px-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="javascript:void(0);">Sales</a>
            </li>
        </ol>
    </nav>
</div>
<div class="houmanity-card">
    <div class="card-body card-head">
        <div class="d-md-flex gap-4 align-items-center bg-white p-3">
            <div class="d-none d-md-flex">All Orders</div>
            <div class="d-md-flex gap-4 align-items-center">
                <form class="mb-3 mb-md-0">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <select class="form-select classic order-table">
                                <option hidden>Sort by</option>
                                <option value="desc">Desc</option>
                                <option value="asc">Asc</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
          
        </div>
    </div>
</div>
<div class="houmanity-card">
    <div class="card-body card-head">
        <div class="d-md-flex gap-4 align-items-center bg-white p-3">
            <div class="w-100 gap-4 align-items-center">
                <form action="{{route('sales_report')}}" method="get" class="row mb-3 mb-md-0">
                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="status-search">
                                    <select name="status" id="Status" class="form-control select2-status" data-created = "{{Auth::user()->id}}">
                                        <option value="" @if(request()->get('status'))
                                            @if(request()->get('status') == "") selected @endif
                                        @endif>Select Status</option>
                                        <option value="1" @if(request()->get('status'))
                                            @if(request()->get('status') == 1) selected @endif
                                        @endif>Pending</option>
                                        <option value="2" @if (request()->get('status'))
                                            @if(request()->get('status') == 2) selected @endif
                                        @endif>Confirmed</option>
                                        <option value="3" @if (request()->get('status'))
                                            @if(request()->get('status') == 3) selected @endif
                                        @endif>Delivered</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="staff-search">
                                    <?php $staffs = DB::table('users')->where('role',3)->get(); ?>
                                    <select name="agent" id="Search" class="form-control select2-staff" data-created = "{{Auth::user()->id}}">
                                        <option value="" selected hidden>Select Agent</option>
                                        @foreach($staffs as $staff)
                                            <option value="{{$staff->id}}" @if (request()->get('agent'))
                                                @if(request()->get('agent') == $staff->id) selected @endif
                                            @endif>{{$staff->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h6 class="mt-2 mb-0">Date Filter</h6>
                            </div>
                            <div class="col-md-4 mt-2">
                                        <div class="district-search">
                                            <select name="date_filter" id="Search" class="form-control select2 date_filter">
                                                    {{-- <option value="">Select Date Filter Column</option> --}}
                                                    <option value="1" @if (request()->get('date_filter'))  @if(request()->get('date_filter') == 1) selected @endif @endif>Created Date</option>
                                                    <option value="2" @if (request()->get('date_filter'))  @if(request()->get('date_filter') == 2) selected @endif @endif>Confirm Date</option>
                                                    <option value="3" @if (request()->get('date_filter'))  @if(request()->get('date_filter') == 3) selected @endif @endif>Delivered Date</option>
                                            </select>
                                            <label class="m-0">Select Date Type</label>
                                        </div>
                                    </div>
                            <div class="col-md-4 mt-2">
                                        <input type="text" name="start_date" class="form-control" id="datefilterfrom"  @if (request()->get('start_date')) value="{{request()->get('start_date')}}" @endif data-date-split-input="true">
                                        <label class="m-0">Select Start Date</label>
                                    </div>
                            <div class="col-md-4 mt-2">
                                        <input type="text" name="end_date" class="form-control" id="datefilterto"  @if (request()->get('end_date')) value="{{request()->get('end_date')}}" @endif data-date-split-input="true">
                                        <label class="m-0">Select End Date</label>
                                    </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-between align-items-center">
                        <input type="submit" value="Search" class="btn btn-primary" name="submit">
                        <a href="{{route('sales_report')}}" class="float-right"><i class="bi bi-bootstrap-reboot"></i> Refresh</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="houmanity-card">
    <div class="card-body table-responsive">
        <table id="testTable" class="table table-custom rwd-table example1" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Amount</th>
                    <th>Products</th>
                    <th>Agent Name</th>
                    <th>Address</th>
                    <th>Transport</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody class="records">
                @if (count($orders) > 0)
                    @foreach ($orders as $order)
                        <tr>
                            <td data-th="No">{{$loop->index + 1}}</td>
                            <td data-th="Order ID"><a href="{{ route('order.details',$order->id) }}" class="text-primary">{{$order->order_id}}</a></td>
                            <td data-th="Customer Name">
                                {{$order->customer_name}}
                            </td>
                            <td data-th="Phone Number">
                               {{$order->phone}}
                            </td>
                            <td data-th="Amount">
                               <?php
                                    $amount = 0;
                                    $cnt = 0;
                                    $items = DB::table('order_items')->where('order_id',$order->id)->get(); 
                                ?>
                                @foreach($items as $item)
                                    <?php $amount = $amount+($item->price*$item->quantity); 
                                    $cnt = $cnt+1;
                                    ?>
                                @endforeach
                                {{$order->total}}
                            </td>
                            <td data-th="Products">
                                <?php $i = 0; ?>
                                @foreach($items as $item)
                                <?php $i++; 
                                $product = DB::table('products')->where('id',$item->product_id)->first();
                                if(!blank($product)){
                                    $pr = $product->name;
                                }else{
                                    $pr = '';
                                }
                                $cat = DB::table('categories')->where('id',$product->category)->first();
                                if(!blank($cat)){
                                    $cate = $cat->name;
                                }else{
                                    $cate = '';
                                }
                                ?>
                                    @if ($i < $cnt)
                                       [{{$pr}} [{{$cate}}] * {{$item->quantity}}],<br>
                                    @else
                                        [{{$pr}} [{{$cate}}] * {{$item->quantity}}]<br>
                                    @endif
                                @endforeach
                            </td>
                            <td data-th="Agent Name">
                                <?php 
                                $user = DB::table('users')->where('id',$order->user_id)->first();
                                if(!blank($user)){
                                $agent = DB::table('users')->where('id',$order->agent_id)->first();
                               
                                ?>
                                @if(!blank($agent) && $user->agent !=0)
                                    {{$agent->name}}
                                @endif
                                <?php 
                                }else{
                                    $agent = DB::table('users')->where('id',$order->agent_id)->first();
                                ?>
                                @if(!blank($agent))
                                    {{$agent->name}}
                                @endif
                                <?php } ?>
                            </td>
                            <td data-th="Address"> 
                                @if (!blank($order->floor_no))
                                    {{$order->floor_no}},
                                @endif
                                @if (!blank($order->address))
                                    {{$order->address}},
                                @endif
                                <br>
                                @if (!blank($order->locality))
                                    {{$order->locality}},
                                @endif
                                @if (!blank($order->city))
                                    {{$order->city}},
                                @endif
                                <br>
                                @if (!blank($order->state))
                                    {{$order->state}},
                                @endif
                                India
                            </td>
                            <td data-th="Transport">{{$order->transport}}</td>
                            <td data-th="Created At">{{date($setting->date_format,strtotime($order->created_at))}}</td>
                        </tr>
                    @endforeach
                   
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
{{-- <div class='pagination-container'>
    <nav class="mt-4" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
    </ul>
    </nav>
</div> --}}
@endsection
@section('script')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.12.1/api/sum().js"></script>

    <script>
        
        $(document).on('change','.search-table',function(){
            $('.srh').addClass('d-none');
            // $('.search-1').removeClass('d-none');
            // $('.search-2').removeClass('d-none');
            // $('.search-3').removeClass('d-none');
            // $('.search-4').removeClass('d-none');
            var select = $(this).val();
            if(select == 1){
                $('.search-1').removeClass('d-none');
                // $('.search-2').addClass('d-none');
                // $('.search-3').addClass('d-none');
            }else if(select == 2){
                $('.search-2').removeClass('d-none');
                // $('.search-1').addClass('d-none');
                // $('.search-3').addClass('d-none');
            }else if(select == 3){
                $('.search-3').removeClass('d-none');
                // $('.search-1').addClass('d-none');
                // $('.search-2').addClass('d-none');
            }else if(select == 4){
                $('.search-4').removeClass('d-none');
            }else if(select == 5){
                $('.search-5').removeClass('d-none');
            }
        });
         function filterRows() {
          var from = $('#datefilterfrom').val();
          var to = $('#datefilterto').val();
        
          if (!from && !to) { // no value for from and to
            return;
          }
        
          from = from || '1970-01-01'; // default from to a old date if it is not set
          to = to || '2999-12-31';
        
          var dateFrom = moment(from, "DD/M/YYYY");
          var dateTo = moment(to, "DD/M/YYYY");
          var amt = 0;
          $('#testTable tbody tr').each(function(i, tr) {
            var val = $(tr).find("td:nth-child(9)").text();
            var dateVal = moment(val, "DD/M/YYYY");
            console.log(dateVal);
            var visible = (dateVal.isBetween(dateFrom, dateTo, null, [])) ? "" : "none"; // [] for inclusive
            $(tr).css('display', visible);
            
            if(visible == ""){
                var amount =$(tr).find("td:nth-child(5)").text();
                amt = amt+parseInt(amount);
            }
          }); 
          $('.pageTotal').html(amt);
        }
        $(document).ready(function(){
            // filterRows();
            $('#datefilterfrom').on("change", filterRows);
            $('#datefilterto').on("change", filterRows);
        });
        $(document).ready(function() {
            $('.example1').DataTable( {
                dom: 'Bfrtip',
                bPaginate: false,
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ],
                buttons: [
                    { extend: 'copyHtml5', footer: true },
                    { extend: 'excelHtml5', footer: true },
                    { extend: 'csvHtml5', footer: true },
                    { extend: 'pdfHtml5', footer: true },
                    { extend: 'print', footer:true}
                ],
                footerCallback: function (row, data, start, end, display) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
 
            // Total over all pages
            total = api
                .column(4)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Total over this page
            pageTotal = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
            // Update footer
            $(api.column(4).footer()).html('<span class="pageTotal">'+pageTotal+'</span>');
            $(api.column(0).footer()).html('Total: ');
        },
            } );
            
        });
    </script>
@endsection
