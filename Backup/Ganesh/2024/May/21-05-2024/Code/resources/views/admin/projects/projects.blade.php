@extends('admin.layouts.app')

@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Projects</h3>
            </div>
            <div class="">
                <a href="{{ route('addprojects') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-plus me-2"></i> Add Project
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="projects_table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="id_column">Project Id</th>
                            <th class="id_column"> ID</th>
                            {{-- <th>Project ID</th> --}}
                            <!-- <th>Project Name</th> -->
                            <th class="customer_column">Customer</th>
                            <th class="reference_column">Reference Name</th>
                            <th class="phone_column">Phone</th>
                            {{-- <th>Quotation File </th> --}}
                            <th class="measurement_column">Estimate Measurement Date</th>
                            <th class="startdate_column">Start Date</th>
                            {{-- <th>Created At</th> --}}
                            <!-- <th>End Date</th> -->
                            <th class="status_column">Status</th>
                            <th class="action_column">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($projects))
                        @php $i = 1; @endphp
                            @foreach ($projects as $project)
                                <tr>
                                    <td> {{ $project->id }} </td>
                                    <td> {{ $i++ }} </td>
                                    {{-- <td><a
                                            href="{{ route('view.completed.project', $project->id) }}">{{ $project->project_generated_id }} - {{ $project->customer->name }}</a>
                                    </td> --}}
                                    <td>
                                        @if ($project->customer)
                                        <a href="{{ route('view.completed.project', $project->id) }}">{{ $project->customer->name }}</a>
                                         @else
                                             No Customer
                                         @endif
                                    </td>
                                    <td>
                                         
                                            {{ $project->reference_name }}
                                    </td>
                                    <td>
                                        @if ($project->customer)
                                            {{ $project->customer->phone }}
                                        @else
                                            No User
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($project->measurement_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</td>
                                    {{-- <td>{{ date('d/m/Y - H:i:s', strtotime($project->created_at)) }}</td> --}}
                                    <!-- <td>{{ \Carbon\Carbon::parse($project->end_date)->format('d/m/Y') }}</td> -->
                                    <td>
                                        @if ($project->status == 0)
                                            <span class="badge bg-warning text-dark">Not Started</span>
                                        @elseif($project->status == 1)
                                            <span class="badge bg-info">In Progress</span>
                                            <p class="mb-0">Step:
                                                <span class="text-danger">
                                                    @if($project->step == 0)
                                                        Project Created
                                                    @elseif ($project->step == 1)
                                                        Measurement
                                                    @elseif ($project->step == 2)
                                                        Quotation
                                                    @elseif ($project->step == 3)
                                                        Purchase
                                                    @elseif ($project->step == 4)
                                                        Workshop @if(!blank($project->sub_step)) - {{$project->sub_step}} @endif
                                                    @elseif ($project->step == 5)
                                                        Fitting
                                                    @endif
                                                </span>
                                            </p>
                                        @elseif($project->status == 2)
                                            <span class="badge bg-success">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('view.project', $project->id) }}" class="editUser"><i
                                                data-feather="edit"></i></a>
                                        <a href="javascript:void(0);" data-id="{{ $project->id }}"
                                            class="ms-2 delete-btn project_delete_btn"><i data-feather="trash-2"></i></a>
                                        {{-- <a href="{{ route('view.completed.project', $project->id) }}" class="editUser"><i
                                        data-feather="eye"></i></a> --}}
                                        {{-- <a href="{{ route('convert.to.lead', ['projectId' => $project->id]) }}"
                                            class="ms-2 convert_lead" data-project-id="{{ $project->id }}">
                                            <i data-feather="rotate-cw"></i>
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    {{-- <tfoot>
                        <tr>
                            <th class="search_id_column"> ID</th>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th class="search_customer_column">Customer</th>
                            <th class="search_reference_column">Reference Name</th>
                            <th class="search_phone_column">Phone</th>
                            <th>Quotation File </th>
                            <th class="search_measurement_column">Estimate Measurement Date</th>
                            <th class="search_startdate_column">Start Date</th>
                            <th>Created At</th>
                            <th>End Date</th>
                            <th class="search_status_column">Status</th>
                            <th class="search_action_column">Action</th>
                        </tr>
                    </tfoot> --}}
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function(){
            $("#projects_table tfoot th").each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            });
            var table = $('#projects_table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                "columnDefs": [
                    { "visible": false, "targets": 0 } // Hide the first column (ID)
                ],
                pageLength: 30,
                buttons: [
                    'copy', 'excelFlash', 'excel', 'print', {
                        text: 'Reload',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                initComplete: function(settings, json) {
                    var footer = $("#projects_table tfoot tr");
                    $("#projects_table thead").append(footer);
                }
            });
            $("#projects_table thead").on("keyup", "input", function() {
                table.column($(this).parent().index())
                    .search(this.value)
                    .draw();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.project_delete_btn', function() {
                var project_id = $(this).attr('data-id');
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
                            url: "{{ route('delete.project', '') }}" + "/" + project_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                if(data == 'success'){
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: "Project has been deleted.",
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
                                        title: 'Please Delete all the related Project tasks to delete project!',
                                        text: "Cannot Delete Project.",
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
        });

        // In your blade file or script section

        //  $(document).ready(function() {
        //     $(document).on('click', '.convert_lead', function() {
        //         var projectId = $(this).attr('data-project-id');
        //         Swal.fire({
        //             title: 'Are you sure?',
        //             text: "This will convert the project back to a lead.",
        //             icon: 'warning',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Yes, convert it!'
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 $.ajax({
        //                     url: "{{ route('convert.to.lead', '') }}" + "/" + projectId,
        //                     type: 'GET',
        //                     dataType: 'json',
        //                     success: function(data) {
        //                         if (data.success) {
        //                             Swal.fire({
        //                                 title: 'Converted!',
        //                                 text: "Project has been converted back to a lead.",
        //                                 icon: 'success',
        //                                 showCancelButton: false,
        //                                 confirmButtonColor: '#3085d6',
        //                                 cancelButtonColor: '#d33',
        //                                 confirmButtonText: 'Ok'
        //                             }).then((result) => {
        //                                 if (result.isConfirmed) {
        //                                     // Reload the leads page
        //                                     window.location.href = "{{ route('leads') }}";
        //                                 }
        //                             });
        //                         } else {
        //                             Swal.fire('Error', 'Conversion failed.', 'error');
        //                         }
        //                     },
        //                     error: function() {
        //                         Swal.fire('Error', 'Something went wrong.', 'error');
        //                     }
        //                 });
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
