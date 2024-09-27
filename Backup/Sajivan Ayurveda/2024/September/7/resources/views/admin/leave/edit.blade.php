@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Appply Leave</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('leave.update', $leave->id) }}" enctype="multipart/form-data" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $leave->id ?? '' }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Leave Type</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="leave_type" class="form-control">
                                    <option value="">Select Leave Type</option>
                                    <option value="sick_leave" @if ($leave->leave_type == 'sick_leave') selected @endif>Sick Leave
                                    </option>
                                    <option value="casual_leave" @if ($leave->leave_type == 'casual_leave') selected @endif>Casual
                                        Leave</option>
                                </select>
                                @error('leave_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Leave Feature</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="leave_feature" class="form-control">
                                    <option value="">Select Leave Feature</option>
                                    <option value="0" @if ($leave->leave_feature == '0') selected @endif>Half Day
                                    </option>
                                    <option value="1" @if ($leave->leave_feature == '1') selected @endif>Full Day
                                    </option>
                                </select>
                                @error('leave_feature')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Leave From</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="date" name="leave_from" class="form-control" placeholder="Enter Leave From"
                                    min="{{ date('Y-m-d') }}" id="leave_from"
                                    value="{{ $leave->leave_from }}">
                                @error('leave_from')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Leave To</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="date" name="leave_to" class="form-control" placeholder="Enter Leave From"
                                    min="{{ date('Y-m-d') }}" id="leave_to"
                                    value="{{ $leave->leave_to}}">
                                @error('leave_to')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">leave Reason</label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="leave_reason" class="form-control" placeholder="Enter Leave Reason">{{$leave->reason }}</textarea>
                                @error('leave_reason')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Attachment</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="file" name="attachment" class="form-control"
                                    placeholder="Upload Attachment">
                                @error('attachment')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('leave.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $('#leave_from').on('change', function() {
            var date = $('#leave_from').val()
            $('#leave_to').val(date)
        });
        $('form').on('submit',function(e){
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
    </script>
@endsection
