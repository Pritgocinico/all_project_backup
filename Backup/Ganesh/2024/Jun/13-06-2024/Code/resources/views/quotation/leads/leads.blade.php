@extends('quotation.layouts.app')
@section('content')
    @if (Session::has('success'))
        <script>
            Swal.fire('success', "Session::get('message')", 'success');
        </script>
    @endif
    <div class="project">
        <div class="page-header d-md-flex justify-content-between  align-items-center">
            <div class="">
                <h3 class="mb-0">Leads</h3>
            </div>
            <div class="">
                <a href="{{ route('quotation_addleads') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-plus me-2"></i> Add Leads
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="leads_table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Lead ID</th>
                            <th>Customer</th>
                            {{-- <th>Reference Name</th> --}}
                            <th>Phone</th>
                            <th>Email Address</th>
                            {{-- <th>Created At</th> --}}
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($leads))
                            @foreach ($leads as $lead)
                                <tr>
                                    <td><a href="{{route('quotation_view.lead.project',$lead->id)}}">{{ $lead->lead_no }}</a></td>
                                    <td>
                                        @if ($lead->customer)
                                            {{ $lead->customer->name }}
                                        @else
                                            No Customer
                                        @endif
                                    </td>
                                    {{-- <td>{{ $lead->reference_name }}</td> --}}
                                    <td>
                                        @if ($lead->customer)
                                            {{ $lead->phone_number }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($lead->customer)
                                            {{ $lead->email }}
                                        @endif
                                    </td>
                                    {{-- <td>{{ date('d/m/Y - H:i:s', strtotime($lead->created_at)) }}</td> --}}
                                    <td>
                                        @if ($lead->lead_status == 1)
                                            <span class="badge bg-info">Lead Placed</span>
                                            <p class="mb-0">Step:
                                                <span class="text-danger">
                                                    @if($lead->step == 0)
                                                        Lead Created
                                                    @elseif ($lead->step == 1)
                                                        Measurement
                                                    @elseif ($lead->step == 2)
                                                        Quotation
                                                    @endif
                                                </span>
                                            </p>
                                        @elseif($lead->lead_status == 2)
                                            <span class="badge bg-warning text-dark">Lead Accepted</span>
                                        @elseif($lead->lead_status == 3)
                                            <span class="badge bg-secondary">Hold</span>
                                        @else
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('quotation_view.lead', $lead->id) }}" class="editUser"><i
                                                    data-feather="edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{ $lead->id }}"
                                                class="ms-2 delete-btn project_delete_btn"><i data-feather="trash-2"></i></a>
                                            {{-- <span class="badge bg-success ms-2">
                                                                <a href="{{ route('convert.to.project', $lead->id) }}"
                                            class="text-light convert_project">Convert To Project</a>
                                            </span> --}}
                                                {{-- @if ($lead->lead_status != 2)
                                                            <span class="badge bg-success ms-2">
                                                                <a href="javascript:void(0);" class="text-light convert_project"
                                                                    data-lead-id="{{ $lead->id }}"
                                            id="convertBtn{{ $lead->id }}">Convert To Project</a>
                                            </span>
                                            @else
                                            <span class="badge bg-secondary ms-2" disabled>Converted</span>
                                            @endif --}}
                                            <!-- <span class="badge bg-success ms-2">
                                                <a href="" class="text-light convert_project" data-lead-id="{{ $lead->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">Convert To Project</a>
                                            </span> -->
                                            {{-- <a href="{{ route('create.project', $lead->id) }}" class="ms-2"><i
                                                    data-feather="rotate-cw"></i></a> --}}
                                            <!-- <a href="{{ route('create.project', $lead->id) }}" class="text-success">Convert</a> -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            {{-- <th>Reference Name</th> --}}
                            <th>Phone</th>
                            <th>Email Address</th>
                            {{-- <th>Created At</th> --}}
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $("#leads_table tfoot th").each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            });
            var table = $('#leads_table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                buttons: [
                    'copy', 'excelFlash', 'excel', 'print', {
                        text: 'Reload',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                initComplete: function(settings, json) {
                    var footer = $("#leads_table tfoot tr");
                    $("#leads_table thead").append(footer);
                }
            });
            $("#leads_table thead").on("keyup", "input", function() {
                table.column($(this).parent().index())
                    .search(this.value)
                    .draw();
            });
        });
        
        
        $(document).ready(function() {
            $(document).on('click', '.project_delete_btn', function() {
                var leads_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this Project!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('quotation_delete.project', '') }}" + "/" + leads_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                if(data == 'success'){
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: "Lead has been deleted.",
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
                                }else{
                                    Swal.fire({
                                        title: 'Please Delete all the related Project lead tasks to delete project lead!',
                                        text: "Cannot Delete Project Lead.",
                                        icon: 'error',
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
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.convert_project', function() {
                var leadId = $(this).attr('data-lead-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will convert the lead to a project.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, convert it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('quotation_convert.to.project', '') }}" + "/" + leadId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                $('#convertBtn' + leadId).attr('disabled', true);
                                $('#convertBtn' + leadId).closest('td').find(
                                        '.lead-status')
                                    .text('Lead Accepted');
                                Swal.fire({
                                    title: 'Converted!',
                                    text: "Lead has been converted to a project.",
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
            $(document).on('click', '#Customername', function() {
                var id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('get_user') }}',
                    data: {
                        'id': id
                    },
                    success: function(data) {
                        $('#email').val(data.email);
                        $('#phone').val(data.phone);
                        $('#addressone').val(data.address);
                        $('#zipcode').val(data.zipcode);
                        $('#cityname').val(data.city).trigger('change');
                    },
                    error: function(data) {
                    }
                });
            });
        });
    </script>
@endsection
