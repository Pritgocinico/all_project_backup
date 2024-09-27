@extends('quotation.layouts.app')

@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Pending Project Reports</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <a class="btn btn-primary" href="{{ route('quotation.pending.reports.export') }}">Export</a>
                    </div>
                </div>
                <table id="report_table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Phone Number</th>
                            <th>Estimate Measurement Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($projects))
                            @foreach ($projects as $project)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a href="{{ route('view.project', $project->id) }}">{{ $project->project_generated_id }}
                                            - {{ $project->customer->name }}</a></td>
                                    <td>{{ $project->phone_number }}</td>
                                    <td>{{ date('d/m/Y', strtotime($project->measurement_date)) }}</td>
                                    <td>
                                        @if ($project->status == 0)
                                            <span class="badge bg-warning text-dark">Not Started</span>
                                        @else
                                            <span class="badge bg-info">In Progress</span>
                                            <p class="mb-0">Step:
                                                <span class="text-danger">
                                                    @if ($project->step == 0)
                                                        Project Created
                                                    @elseif ($project->step == 1)
                                                        Measurement
                                                    @elseif ($project->step == 2)
                                                        Quotation
                                                    @elseif ($project->step == 3)
                                                        Purchase
                                                    @elseif ($project->step == 4)
                                                        Workshop @if (!blank($project->sub_step)) -
                                                            {{ $project->sub_step }} @endif
                                                    @elseif ($project->step == 5)
                                                        Site Installation
                                                    @endif
                                                </span>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Project Profit</th>
                            <th>Created At</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var table = $('#report_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excelFlash', 'excel', 'print', {
                text: 'Reload',
                action: function (e, dt, node, config) {
                    dt.ajax.reload();
                }
            }
        ],
    });
    </script>
@endsection
