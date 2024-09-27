@extends('quality_analytic.layouts.app')
@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Edit Tasks</h3>
            </div>
            <div class="">
                <a href="{{ route('qa_task-management') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="alert-repeater" action="{{ route('qa_updateTask', $task->id) }}" enctype="multipart/form-data"
                    method="POST">
                    @csrf
                    <div class="form-row row">
                        <div class="form-group col-md-6">
                            <label for="Customername">Task <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="task" id="task"
                            value="{{ $task->task }}" placeholder="Task">
                            @if ($errors->has('task'))
                                <span class="text-danger">{{ $errors->first('task') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Project <span class="text-danger">*</span></label>
                            <select class="form-control select2-example" id="project_id" name="project_id">
                                <option value="" selected disabled>Select Project</option>
                                @foreach($projects as $project)
                                <option <?php if($project->id == $task->project_id){ echo "selected"; } ?> value="{{$project->id}}">{{$project->project_generated_id ?? $project->lead_no }} - {{ $project->customer->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('project_id'))
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Task Type <span class="text-danger">*</span></label>
                            <select class="form-control select2-example" id="task_type" name="task_type">
                                <option value="" selected disabled>Select Type</option>
                                <option <?php if($task->task_type == 'workshop'){ echo "selected"; } ?> value="workshop">Workshop</option>
                                <option <?php if($task->task_type == 'fitting'){ echo "selected"; } ?> value="fitting">Fitting</option>
                                <option <?php if($task->task_type == 'quotation'){ echo "selected"; } ?> value="quotation">Quotation</option>
                                <option <?php if($task->task_type == 'measurement'){ echo "selected"; } ?> value="measurement">Measurement</option>
                                <option <?php if($task->task_type == 'purchase'){ echo "selected"; } ?> value="purchase">Purchase</option>
                                <option <?php if($task->task_type == 'site_installation'){ echo "selected"; } ?> value="site_installation">Site Installation</option>
                                <option <?php if($task->task_type == 'office'){ echo "selected"; } ?> value="office">Office</option>
                            </select>
                            @if ($errors->has('task_type'))
                                <span class="text-danger">{{ $errors->first('task_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Status <span class="text-danger">*</span></label>
                            <select class="form-control select2-example" id="status" name="status">
                                <option value="" selected disabled>Select Status</option>
                                <option <?php if($task->task_status == 'completed'){ echo "selected"; } ?> value="completed">Completed</option>
                                <option <?php if($task->task_status == 'pending'){ echo "selected"; } ?> value="pending">Pending</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="taskDate">Date <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="taskDate" class="form-control" value="{{ date('Y-m-d\TH:i',strtotime($task->task_date)) }}">
                            @if ($errors->has('taskDate'))
                                <span class="text-danger">{{ $errors->first('taskDate') }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary mt-2">Submit</button>

                </form>

            </div>

        </div>

    </div>
@endsection

@section('script')

<script>
    $('#project_id').select2();
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
