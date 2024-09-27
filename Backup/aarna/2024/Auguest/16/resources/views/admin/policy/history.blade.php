@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-no-border">
                <h4>Policy No - {{ $policy->policy_no }}</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive custom-scrollbar">
                    <table class="display border" id="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Policy No</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Insurance Type</th>
                                <th>Business Type</th>
                                <th>Vehicle Make/Model</th>
                                <th>Vehicle Reg. No</th>
                                <th>Vehicle Chassis No</th>
                                <th>NetPremium</th>
                                <th>Risk Date</th>
                                <th>Payout Restricted</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!blank($policies))
                                @foreach ($policies as $source)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td><a href="{{ route('admin.view_policy', $source->id) }}"
                                                style="color:blue !important;">{{ $source->policy_no }}</a></td>
                                        <td>
                                            @foreach ($customers as $customer)
                                                @if ($customer->id == $source->customer)
                                                    {{ $customer->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($source->insurance_type == 1)
                                                @foreach ($categories as $cats)
                                                    @if ($cats->id == $source->category)
                                                        {{ $cats->name }}
                                                    @endif
                                                @endforeach
                                            @else
                                                @if ($source->health_category == 1)
                                                    Base
                                                @elseif($source->health_category == 2)
                                                    Personal Accident
                                                @elseif($source->health_category == 3)
                                                    Super Topup
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if ($source->insurance_type == 1)
                                                Motor Insurance
                                            @else
                                                Health Insurance
                                            @endif
                                        </td>
                                        <td>
                                            @if ($source->insurance_type == 1)
                                                @if ($source->business_type == 1)
                                                    New
                                                @elseif ($source->business_type == 2)
                                                    Renewal
                                                @elseif ($source->business_type == 3)
                                                    Rollover
                                                @elseif ($source->business_type == 4)
                                                    Used
                                                @endif
                                            @else
                                                @if ($source->business_type == 1)
                                                    New
                                                @elseif ($source->business_type == 2)
                                                    Renewal
                                                @elseif ($source->business_type == 3)
                                                    Portability
                                                @endif
                                            @endif
                                        </td>
                                        <td>

                                            @if (!blank($source->vehicle_make))
                                                {{ $source->vehicle_make }}, {{ $source->vehicle_model }}
                                            @endif

                                        </td>
                                        <td>

                                            @if (!blank($source->vehicle_registration_no))
                                                {{ $source->vehicle_registration_no }}
                                            @endif

                                        </td>
                                        <td>

                                            @if (!blank($source->vehicle_chassis_no))
                                                {{ $source->vehicle_chassis_no }}
                                            @endif

                                        </td>

                                        <td>

                                            @if (!blank($source->net_premium_amount))
                                                {{ number_format($source->net_premium_amount, 2, '.', '') }}
                                            @endif

                                        </td>

                                        <td>

                                            <span>{{ date('d-m-Y', strtotime($source->risk_start_date)) }}/<br>
                                                {{ date('d-m-Y', strtotime($source->risk_end_date)) }}</span>

                                        </td>
                                        <td class="text-capitalize">
                                            {{ isset($source->payout_restricted) ? $source->payout_restricted : 'No' }}
                                        </td>
                                        <td>
                                            <ul class="action">
                                                <li class="edit"> <a href="{{ route('admin.view_policy', $source->id) }}"
                                                        title="View Policy"><i class="icon-eye"></i></a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                var user_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('delete.policy', '') }}" + "/" + user_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Policy has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#history-table').DataTable({
                "order": [
                    [6, "desc"]
                ]
            });
        });
    </script>
@endsection
