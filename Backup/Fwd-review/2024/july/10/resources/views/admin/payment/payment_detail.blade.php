@extends('admin.layouts.app')
<link rel="stylesheet" href="{{ url('/') }}/assets/Css/date-range-picker/daterangepicker.css">
@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">Payment Details</div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive p-3">
                <div class="row">
                    <div class="col-md-8">
                    </div>
                    <div class="col-md-4 form-floating mb-3">
                        <input type="text" class="form-control date_filter" name="expire_at" id="expire_at"
                            value="" placeholder="" />
                        <label for="Name" class="form-label">Expire At</label>
                    </div>
                </div>
                <table id="example154" class="table rwd-table mb-0">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Business Name</th>
                            <th>Transaction Number</th>
                            <th>Plan Title</th>
                            <th>Tax Amount</th>
                            <th>Gateway</th>
                            <th>Expired At</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($paymentList))
                            @foreach ($paymentList as $payment)
                                <tr>
                                    <td data-header="Name" class="pt-2">
                                        {{ isset($payment->clientDetail) ? $payment->clientDetail->name : '-' }}
                                    </td>
                                    <td data-header="Name" class="pt-2">
                                        {{ isset($payment->businessDetail) ? $payment->businessDetail->name : '-' }}
                                    </td>
                                    <td data-header="Email">{{ $payment->transaction_number }}</td>
                                    <td data-header="Phone Number">{{ $payment->plan_title }}</td>
                                    <td data-header="Phone Number">$ {{ $payment->tax_amount }}</td>
                                    <td data-header="Email">{{ $payment->gateway }}</td>

                                    <td data-header="Created At">{{ date('Y-m-d', strtotime($payment->expiry_date)) }}</td>
                                    <td data-header="Created At">{{ date('Y-m-d h:i:s', strtotime($payment->created_at)) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('/') }}/assets/JS/momentjs/moment.min.js"></script>
    <script src="{{ url('/') }}/assets/JS/date-range-picker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#example154').DataTable(
                {
                "columnDefs": [{
                    "targets": [6, 7], // Apply to Created At and Expired At columns
                    "render": function(data, type, row) {
                        return moment(data).format('YYYY-MM-DD');
                    }
                }]
            });
            var expiredPicker = $('#expire_at').daterangepicker({
                autoUpdateInput: false,
            }, function(start, end, label) {
                $('#expire_at').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
                table.draw();
            });
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var expiredMin = expiredPicker.val().split('/')[0];
                    var expiredMax = expiredPicker.val().split('/')[1];
                    var expiredAt = moment(data[6], 'YYYY-MM-DD');
                    var expiredFilter = $('#expire_at').val();
                    if (expiredFilter !== '') {
                        if ((expiredMin == null && expiredMax == null) ||
                            (expiredAt.isSameOrAfter(expiredMin) && expiredAt.isSameOrBefore(expiredMax))) {
                            return true;
                        }
                        return false;
                    }
                }
            )
        });
    </script>
@endsection
