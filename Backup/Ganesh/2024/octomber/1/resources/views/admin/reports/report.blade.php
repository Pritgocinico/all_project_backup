@extends('admin.layouts.app')

@section('content')
<div class="project">
    <div class="page-header d-md-flex justify-content-between align-items-center">
        <div class="">
            <h3 class="mb-0">Completed Project Reports</h3>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <a class="btn btn-primary" href="{{route('reports.export')}}">Export</a>
                </div>
            </div>
            <table id="report_table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Project</th>
                        <th>Project Profit</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!blank($projects))
                    @foreach($projects as $d_project)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td><a href="{{ route('view.project', $d_project->id) }}" class="text-dark">{{ $d_project->project_generated_id }} - {{ $d_project->customer->name }}</a></td>
                        <td> &#8377; {{ $d_project->margin_cost }}</td>
                        <td>{{ date('d/m/Y - H:i:s', strtotime($d_project->created_at)) }}</td>
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