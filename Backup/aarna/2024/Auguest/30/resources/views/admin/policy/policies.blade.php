@extends('admin.layouts.app')

@section('style')

    <style>
        svg {

            vertical-align: middle !important;

        }

        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap.min.css">
    </style>

@section('content')

    <div class="container-fluid">

        <div class="row">

            <div class="col-sm-12">

                <div class="card">

                    <div class="card-header pb-0 card-no-border  justify-content-between d-flex">

                        <h4>Insurance Policy List</h4>

                        <div>

                            <a href="{{ route('admin.policies') }}" class="btn btn-secondary ms-2"> <i
                                    class="fa fa-refresh"></i> Refresh</a>

                            @if (Auth::user()->role == 1)

                                <a href="{{ route('admin.add.policy') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                                    Add New Policy</a>
                            @else
                                @foreach ($permissions as $permission)
                                    @if ($permission->capability == 'policy-create' && $permission->value == 1)
                                        <a href="{{ route('admin.add.policy') }}" class="btn btn-primary"><i
                                                class="fa fa-plus"></i> Add New Policy</a>
                                    @endif
                                @endforeach

                            @endif

                        </div>

                    </div>

                    <div class="card-body table-responsive">

                        <table class="table rwd-table mb-0 w-100">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>Policy No</th>

                                    <th>Customer</th>
                                    <th>Category</th>
                                    <th> Sub Category</th>

                                    <th>Business Type</th>

                                    <th>Vehicle Make/Model</th>

                                    <th>Vehicle Reg. No</th>

                                    <th>Vehicle Chassis No</th>

                                    <th>NetPremium</th>

                                    <th>Risk Date</th>
                                    <th>Payout Restricted</th>

                                    <th>Action</th>

                                </tr>

                                <tr>

                                    <form action="{{ route('admin.policies') }}" method="get">

                                        <th>

                                            <input type="hidden" name="search" value="1">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="policy_no"
                                                placeholder="Policy No"
                                                @if (isset($_GET['policy_no'])) value="{{ $_GET['policy_no'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="customer"
                                                placeholder="Customer Name"
                                                @if (isset($_GET['customer']) && $_GET['customer'] !== '') value="{{ $_GET['customer'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="category"
                                                placeholder="Category Name"
                                                @if (isset($_GET['category']) && $_GET['category'] !== '') value="{{ $_GET['category'] }}" @endif
                                                id="">

                                        </th>
                                        <th>

                                            <input type="text" class="form-control" name="sub_category"
                                                placeholder="Sub Category Name"
                                                @if (isset($_GET['sub_category']) && $_GET['sub_category'] !== '') value="{{ $_GET['sub_category'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="business_type"
                                                placeholder="Business Type"
                                                @if (isset($_GET['business_type']) && $_GET['business_type'] !== '') value="{{ $_GET['business_type'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="vehicle_make"
                                                placeholder="Vehicle Make"
                                                @if (isset($_GET['vehicle_make']) && $_GET['vehicle_make'] !== '') value="{{ $_GET['vehicle_make'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="vehicle_registration_no"
                                                placeholder="Vehicle registration no"
                                                @if (isset($_GET['vehicle_registration_no']) && $_GET['vehicle_registration_no'] !== '') value="{{ $_GET['vehicle_registration_no'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="vehicle_chassis_no"
                                                placeholder="Vehicle chassis no"
                                                @if (isset($_GET['vehicle_chassis_no']) && $_GET['vehicle_chassis_no'] !== '') value="{{ $_GET['vehicle_chassis_no'] }}" @endif
                                                id="">

                                        </th>

                                        <th>

                                            <input type="text" class="form-control" name="net_premium"
                                                placeholder="Net Premium"
                                                @if (isset($_GET['net_premium']) && $_GET['net_premium'] !== '') value="{{ $_GET['net_premium'] }}" @endif
                                                id="">

                                        </th>


                                        <th>

                                            <input type="date" class="form-control" name="risk_start_date"
                                                placeholder="Risk start date"
                                                value="@if (isset($_GET['risk_start_date']) && $_GET['risk_start_date'] !== '') {{ date('Y-m-d', strtotime($_GET['risk_start_date'])) }} @endif"
                                                id="">

                                        </th>
                                        <th>
                                            <select name="policy_source" class="form-control" id="policy_source">
                                                <option value="">Select Policy Restricted</option>
                                                <option value="yes" @if (isset($_GET['policy_source']) && $_GET['policy_source'] == 'yes') selected @endif>
                                                    Yes</option>
                                                <option value="no" @if (isset($_GET['policy_source']) && $_GET['policy_source'] == 'no') selected @endif>
                                                    No</option>
                                            </select>
                                        </th>

                                        <th class="d-flex">

                                            <button type="submit" class="btn btn-primary mt-2"> <i
                                                    class="fa fa-search"></i> Search</button>

                                        </th>

                                    </form>

                                </tr>

                            </thead>

                            <tbody>

                                @if (!blank($policies))

                                    @php $i = ($policies->currentpage()-1)* $policies->perpage() + 1;@endphp

                                    @foreach ($policies as $key => $source)
                                        <tr>

                                            <td>{{ $i++ }}</td>

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
                                                @foreach ($categories as $cat)
                                                    @if ($source->insurance_type == 1)
                                                        @if ($cat->id == $source->category)
                                                            {{ $cat->name }}
                                                        @endif
                                                    @else
                                                        @if ($cat->id == $source->health_category)
                                                            {{ $cat->name }}
                                                        @endif
                                                    @endif
                                                    
                                                @endforeach
                                            </td>
                                            <td>

                                                @if ($source->insurance_type == 1)
                                                    @foreach ($sub_categories as $cats)
                                                        @if ($cats->id == $source->sub_category)
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

                                                    @if (Auth::user()->role == 1)
                                                        <li class="edit"><a
                                                                href="{{ route('admin.view_policy', $source->id) }}"
                                                                title="View Policy"><i class="icon-eye"></i></a></li>

                                                        <li class="edit"><a
                                                                href="{{ route('admin.claims', $source->id) }}"
                                                                title="Claim"><i class="icon-save-alt"></i></li>

                                                        <li class="edit"><a
                                                                href="{{ route('admin.endorsement', $source->id) }}"
                                                                title="Endorsement"><i class="icon-check-box"></i></a></li>

                                                        <li class="edit"><a
                                                                href="{{ route('admin.add.covernote', $source->id) }}"
                                                                title="Renew Policy to Covernote"><i
                                                                    class="fa fa-clipboard"></i></a></li>

                                                        <li class="edit"><a
                                                                href="{{ route('admin.renew_policy', $source->id) }}"
                                                                title="Renew"><i class="icon-reload"></i></a></li>

                                                        <li class="edit"><a
                                                                href="{{ route('admin.edit_policy', $source->id) }}"><i
                                                                    class="icon-pencil-alt"></i></a></li>

                                                        <li class="delete"><a href="javascript:void(0);"
                                                                data-id="{{ $source->id }}" class="delete-btn"
                                                                title="Delete"><i class="icon-trash"></i></a></li>

                                                        <li class="edit"><a href="javascript:void(0);"
                                                                data-bs-toggle="modal" data-policy="{{ $source->id }}"
                                                                data-bs-target="#staticBackdrop" class="cancelPolicy"><i
                                                                    class="icon-close"></i></a></li>
                                                    @elseif(Auth::user()->role == 3)
                                                        <li class="edit"><a
                                                                href="{{ route('admin.view_policy', $source->id) }}"
                                                                title="View Policy"><i class="icon-eye"></i></a></li>
                                                    @else
                                                        @foreach ($permissions as $permission)
                                                            @if ($permission->capability == 'policy-own-view' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                        href="{{ route('admin.view_policy', $source->id) }}"
                                                                        title="View Policy"><i class="icon-eye"></i></a>
                                                                </li>
                                                            @endif

                                                            @if ($permission->capability == 'policy-claims' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                        href="{{ route('admin.claims', $source->id) }}"
                                                                        title="Claim"><i class="icon-save-alt"></i></li>
                                                            @endif

                                                            @if ($permission->capability == 'policy-endorsement' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                        href="{{ route('admin.endorsement', $source->id) }}"
                                                                        title="Endorsement"><i
                                                                            class="icon-check-box"></i></a></li>
                                                            @endif

                                                            @if ($permission->capability == 'renew-to-covernote' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                        href="{{ route('admin.add.covernote', $source->id) }}"
                                                                        title="Renew Policy to Covernote"><i
                                                                            class="fa fa-clipboard"></i></a></li>
                                                            @endif

                                                            @if ($permission->capability == 'policy-renew' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                        href="{{ route('admin.renew_policy', $source->id) }}"
                                                                        title="Renew"><i class="icon-reload"></i></a>
                                                                </li>
                                                            @endif

                                                            @if ($permission->capability == 'policy-edit' && $permission->value == 1)
                                                                <li class="edit"><a
                                                                        href="{{ route('admin.edit_policy', $source->id) }}"><i
                                                                            class="icon-pencil-alt"></i></a></li>
                                                            @endif

                                                            @if ($permission->capability == 'policy-delete' && $permission->value == 1)
                                                                <li class="delete"><a href="javascript:void(0);"
                                                                        data-id="{{ $source->id }}" class="delete-btn"
                                                                        title="Delete"><i class="icon-trash"></i></a></li>
                                                            @endif

                                                            @if ($permission->capability == 'policy-cancel' && $permission->value == 1)
                                                                <li class="edit"><a href="javascript:void(0);"
                                                                        data-bs-toggle="modal"
                                                                        data-policy="{{ $source->id }}"
                                                                        data-bs-target="#staticBackdrop"
                                                                        class="cancelPolicy"><i
                                                                            class="icon-close"></i></a></li>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </ul>

                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>

                                        <td class="text-center" colspan="11">Policies Not Found.</td>

                                    </tr>

                                @endif

                            </tbody>

                        </table>

                        {{ $policies->appends(request()->query())->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Cancel Modal -->

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="staticBackdropLabel">Cancel Policy</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <form action="{{ route('admin.cancel.policy') }}" method="post">

                    @csrf

                    <div class="modal-body">

                        <h5 class="text-center">Are you sure you want to cancel?</h5>

                        <div class="col-md-12 mt-3">

                            <label for="Date" class="form-label">Please enter date *</label>

                            <input type="date" class="form-control" name="cancel_date" id="Date"
                                value="{{ Carbon\Carbon::today()->format('Y-m-d') }}" placeholder="" required />

                            <span class="text-danger"></span>

                        </div>

                        <div class="col-md-12 mt-3">

                            <label for="Reason" class="form-label">Please enter reason *</label>

                            <textarea class="form-control" name="reason" id="Reason" placeholder=""></textarea>

                            <span class="text-danger"></span>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <input type="hidden" name="id" class="policyID" value="">

                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </div>

                </form>

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

            $(document).on('click', '.cancelPolicy', function() {

                var id = $(this).data('policy');

                $('.policyID').val(id);

            });

        });
    </script>

@endsection
