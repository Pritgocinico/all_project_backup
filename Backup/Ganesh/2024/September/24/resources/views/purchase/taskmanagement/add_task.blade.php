@extends('purchase.layouts.app')
@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Add Tasks</h3>
            </div>
            <div class="">
                <a href="{{ route('purchase_task-management') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form class="alert-repeater" action="{{ route('purchase_store_task') }}" enctype="multipart/form-data"
                    method="POST">
                    @csrf
                    <div class="form-row row">
                        <div class="form-group col-md-6">
                            <label for="Customername">Task <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="task" id="task"
                            value="{{ old('task') }}" placeholder="Task">
                            @if ($errors->has('task'))
                                <span class="text-danger">{{ $errors->first('task') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Project <span class="text-danger">*</span></label>
                            <select class="form-control select2-example" id="project_id" name="project_id">
                                <option value="" selected disabled>Select Project</option>
                                @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->project_generated_id ?? $project->lead_no }} - {{ $project->customer->name }}</option>
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
                                <option value="workshop">Workshop</option>
                                <option value="fitting">Fitting</option>
                                <option value="quotation">Quotation</option>
                                <option value="measurement">Measurement</option>
                                <option value="purchase">Purchase</option>
                                <option value="site_installation">Site Installation</option>
                                <option value="office">Office</option>
                            </select>
                            @if ($errors->has('task_type'))
                                <span class="text-danger">{{ $errors->first('task_type') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Status <span class="text-danger">*</span></label>
                            <select class="form-control select2-example" id="status" name="status">
                                <option value="" selected disabled>Select Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                            </select>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="taskDate">Date <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local" name="taskDate" class="form-control" value="{{date('Y-m-d\TH:i') }}">
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
