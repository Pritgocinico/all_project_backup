@extends('quality_analytic.layouts.app')

@section('content')
<div class="project">
    <div class="page-header align-items-center">
        <div class="row">
            <div class="col-md-9">
                <h3 class="mb-0">Projects</h3>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-10">
                        <form action="{{ route('qa_projects') }}">
                            <select class="form-select" name="project_step" id="project_step">
                                <option value="">Select Step</option>
                                <option value="1">Measurement</option>
                                <option value="2">Quotation</option>
                                <option value="3">Purchase</option>
                                <option value="4">Workshop</option>
                                <option value="5">Site Installation</option>
                                <option value="6">Quality Analytic</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary" id="">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="projects_table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Default Project ID</th>
                        <th>ID</th>
                        <th>Project ID</th>
                        <th>Customer</th>
                        <th>Reference Name</th>
                        <th>Phone</th>
                        <th>Estimate Measurement Date</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($projects))
                    @foreach ($projects as $key=>$project)
                    <tr>
                        <td>{{$project->id}}</td>
                        <td>{{$key+1}}</td>
                        <td><a href="{{ route('qa_view.completed.project', $project->id) }}">{{ $project->project_generated_id }}</a>
                        </td>
                        <td>
                            @if ($project->customer)
                            {{ $project->customer->name }}
                            @else
                            No Customer
                            @endif
                        </td>
                        <td>{{ $project->reference_name }}</td>
                        <td>
                            @if ($project->customer)
                            {{ $project->customer->phone }}
                            @else
                            No User
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($project->measurement_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($project->start_date)->format('d/m/Y') }}</td>
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
                            @if ($project->status == 2)
                            <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Additional Project" onclick="generateAdditionalProject('{{ $project->id }}')" class="generateReport"><i class="fa fa-plus" aria-hidden="true"></i>
                                </i>
                            </a>
                            @endif
                            <a href="{{ route('qa_view.project', $project->id) }}" class="editUser"><i data-feather="edit"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>Default Project ID</th>
                        <th>ID</th>
                        <th>Project ID</th>
                        <th>Customer</th>
                        <th>Reference Name</th>
                        <th>Phone</th>
                        <th>Estimate Measurement Date</th>
                        <th>Start Date</th>
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
    function generateAdditionalProject(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure create additional Project?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes !'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('qa_additional.project', '') }}" + "/" + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == '1') {
                                Swal.fire({
                                    title: 'create!',
                                    text: "Additional Project has been Created.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'qa/view-measurement/' +
                                            data.id;
                                    }
                                });
                            }
                        }
                    });
                }
            });
        }
    $(document).ready(function() {
        $("#projects_table tfoot th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        });
        var table = $('#projects_table').DataTable({
            dom: 'Bfrtip',
            order: [
                [0, 'desc']
            ],
            'pageLength': 30,
            'columnDefs': [{
                'targets': [0],
                'visible': false
            }],
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
</script>
@endsection