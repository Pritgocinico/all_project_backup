@extends('purchase.layouts.app')

@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Logs</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created By</th>
                            <th>Module</th>
                            <th>Logs</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!blank($logs))
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $log->user_id }}</td>
                                    <td>{{ $log->module }}</td>
                                    <td>{{ $log->log }}</td>
                                    <td>{{ date('d/m/Y - H:i:s', strtotime($log->created_at)) }}</td>
                                </tr>
                            @endforeach
                        @endif     
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Created By</th>
                            <th>Module</th>
                            <th>Logs</th>
                            <th>Created At</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection