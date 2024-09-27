@extends('purchase.layouts.app')

@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Task Management</h3>
            </div>
            <div class="">
                <a href="{{ route('purchase_addTask') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-plus me-2"></i> Add Task
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="tasks_table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Task Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Task Date</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($tasks))
                            @foreach ($tasks as $task)
                                @if(!blank($task->project))
                                    <tr>
                                        <td>
                                            {{ $task->project->project_generated_id ?? $task->project->lead_no }} - {{ $task->project->customer->name }}
                                        </td>
                                        <td>
                                            {{ $task->task }}
                                        </td>
                                        <td>
                                            {{ucfirst($task->task_type)}}
                                        </td>
                                        <td>
                                            {{ $task->task_status }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y H:i:s A', strtotime($task->task_date)) }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y - H:i:s', strtotime($task->created_at)) }}
                                        </td>
                                        <td class="d-flex">
                                            <a href="{{route('purchase_editTask', $task->id)}}" class="editTask"><i
                                                    data-feather="edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{$task->id}}"
                                                class="ms-2 delete-btn task_delete_btn"><i data-feather="trash-2"></i></a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Project ID</th>
                            <th>Task Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Task Date</th>
                            <th>Created At</th>
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
            $("#tasks_table tfoot th").each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            });
            var table = $('#tasks_table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                buttons: [
                    'copy', 'excelFlash', 'excel', {
                        extend: 'print',
                        text: 'Print',
                        title: function() {
                            return '<h3><img class="logo" src="{{url('/')}}/assets/media/image/logo-new.png" alt="logo"></h3>';
                        },
                        customize: function(win) {
                            // Select the columns you want to include in the print
                            $(win.document.body).find('table').addClass('print-table');
                            $(win.document.body).find('table.print-table').removeClass('table-striped');

                            // Example: Hide the first column in the print output
                            $(win.document.body).find('table.print-table th:last-child').css('display', 'none');
                            $(win.document.body).find('table.print-table td:last-child').css('display', 'none');
                        }
                    }, {
                        text: 'Reload',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    }
                ],
                initComplete: function(settings, json) {
                    var footer = $("#tasks_table tfoot tr");
                    $("#tasks_table thead").append(footer);
                }
            });
            $("#tasks_table thead").on("keyup", "input", function() {
                table.column($(this).parent().index())
                    .search(this.value)
                    .draw();
            });
        });
        
        $(document).ready(function() {
            $(document).on('click', '.task_delete_btn', function() {
                var task_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this Task!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('purchase_deleteTask', '') }}" + "/" + task_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Task has been deleted.",
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
    </script>
@endsection
