@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border  justify-content-between d-flex">
            <h4>Logs</h4>
            </div>
        <div class="card-body">
          <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>User Name</th>
                        <th>Module</th>
                        <th>Log</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($logs))
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ isset($log->userDetail)? $log->userDetail->name : $log->user_id }}</td>
                                <td>{{ $log->module }}</td>
                                <td>{{ $log->log }}</td>
                                <td>{{ date('Y-m-d h:i:s A', strtotime($log->created_at)) }}</td>

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