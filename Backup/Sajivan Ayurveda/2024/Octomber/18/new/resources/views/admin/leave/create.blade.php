@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Appply Leave</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('leave.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="id" value="{{ $leave->id ?? '' }}">

                        <div class="row align-items-center g-3 mt-6 position-relative">
                            <div class="col-md-2"><label class="form-label mb-0">Leave Type <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="leave_type" class="form-control" id="leave_type_dropdown">
                                    <option value="">Select Leave Type</option>
                                    <option value="paid"  @if ($total_leftLeave <= 0) disabled @endif @if (old('leave_type') == 'paid') selected @endif>Paid
                                    </option>
                                    <option value="unpaid" @if (old('leave_type') == 'unpaid') selected @endif>Unpaid</option>
                                </select>
                                <div class="text-danger paid_count d-none" id="paid_count_div">@if($total_leftLeave <= 0)You've used up all your paid leaves. Leave left : 0 @else Leave Left : {{$total_leftLeave}} @endif</div>
                                @error('leave_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Leave Feature <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="leave_feature" class="form-control" id="leaveFeature">
                                    <option value="">Select Leave Feature</option>
                                    <option value="0" @if (old('leave_feature') == '0') selected @endif>Half Day
                                    </option>
                                    <option value="1" @if (old('leave_feature') == '1') selected @endif>Full Day
                                    </option>
                                </select>
                                @error('leave_feature')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2" id="leave_from_label"><label class="form-label mb-0">Leave From <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4" id="leave_from_div">
                                <input type="date" name="leave_from" class="form-control" placeholder="Enter Leave From"
                                    min="{{ date('Y-m-d') }}" id="leave_from"
                                    value="{{ old('leave_from') ?? date('Y-m-d')  }}">
                                @error('leave_from')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2" id="leave_to_label"><label class="form-label mb-0">Leave To <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4" id="leave_to_div">
                                <input type="date" name="leave_to" class="form-control" placeholder="Enter Leave From"
                                    min="{{ date('Y-m-d') }}" id="leave_to"
                                    value="{{ old('leave_to') ?? date('Y-m-d') }}">
                                @error('leave_to')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2 d-none" id="leaveon_div_label"><label class="form-label mb-0">Leave on <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4 d-none" id="leaveon_div">
                                <input type="date" name="leave_on" class="form-control" placeholder="Enter Leave on"
                                    min="{{ date('Y-m-d') }}" id="leave_on"
                                    value="{{ old('leave_on') ?? date('Y-m-d') }}">
                                @error('leave_on')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 col-xl-2 d-none half_time_div">
                                <label class="form-label mb-0">Half Day Time <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-4 d-none half_time_div">
                                <select name="half_time" id="half_time" class="form-control">
                                    <option value="">Select Half Day Time</option>
                                    <option value="first_half" @if (old('half_time') == 'first_half') selected @endif>First Half ( 9:30 to 2 )</option>
                                    <option value="second_half" @if (old('half_time') == 'second_half') selected @endif>Second Half ( 2 to 7 )</option>
                                </select>
                                @error('half_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Leave Reason <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="leave_reason" class="form-control" id="leave_reason_dropdown">
                                    <option value="">Select Leave Reason</option>
                                    <option value="Family Function" @if (old('leave_reason') == 'Family Function') selected @endif>Family Function
                                    </option>
                                    <option value="Sick Leave" @if (old('leave_reason') == 'Sick Leave') selected @endif>Sick Leave
                                    </option>
                                    <option value="Emergency Leave" @if (old('leave_reason') == 'Emergency Leave') selected @endif>Emergency Leave
                                    </option>
                                    <option value="other" @if (old('leave_reason') == 'other') selected @endif>Other
                                    </option>
                                </select>
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

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2 d-none" id="other_label"><label class="form-label mb-0">Other Reason <span class="text-danger">*</span></label></div>
                            <div class="col-md-4 col-xl-4 d-none" id="other_div">
                                <textarea name="other_reason" class="form-control" placeholder="Enter Leave Reason">{{ old('other_reason') }}</textarea>
                                @error('other_reason')
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

        $('#leave_type_dropdown').on('change', function() {
            if($(this).val() == "unpaid"){
                $('#paid_count_div').addClass('d-none');
            }else{
                $('#paid_count_div').removeClass('d-none');
            }
        });

        $(document).ready(function(){
            // Check the value of leave feature on page load
            if($('#leaveFeature').val() == "0"){
                $('#leaveon_div_label, #leaveon_div').removeClass('d-none');
                $('.half_time_div').removeClass('d-none');
                $('#leave_from_div, #leave_from_label').addClass('d-none');
                $('#leave_to_div, #leave_to_label').addClass('d-none');
            } else {
                $('.half_time_div').addClass('d-none');
                $('#leaveon_div_label, #leaveon_div').addClass('d-none');
                $('#leave_from_div, #leave_from_label').removeClass('d-none');
                $('#leave_to_div, #leave_to_label').removeClass('d-none');
            }

            // Check the value of leave reason on page load
            if($('#leave_reason_dropdown').val() == "other"){
                $('#other_div, #other_label').removeClass('d-none');
            } else {
                $('#other_div, #other_label').addClass('d-none');
            }

            // Trigger change events when leave feature changes
            $('#leaveFeature').on('change', function(){
                if($(this).val() == "0"){
                    $('#leaveon_div_label, #leaveon_div').removeClass('d-none');
                    $('.half_time_div').removeClass('d-none');
                    $('#leave_from_div, #leave_from_label').addClass('d-none');
                    $('#leave_to_div, #leave_to_label').addClass('d-none');
                } else {
                    $('.half_time_div').addClass('d-none');
                    $('#leaveon_div_label, #leaveon_div').addClass('d-none');
                    $('#leave_from_div, #leave_from_label').removeClass('d-none');
                    $('#leave_to_div, #leave_to_label').removeClass('d-none');
                }
            });

            // Trigger change events when leave reason changes
            $('#leave_reason_dropdown').on('change', function(){
                if($(this).val() == "other"){
                    $('#other_div, #other_label').removeClass('d-none');
                } else {
                    $('#other_div, #other_label').addClass('d-none');
                }
            });
        });


        $('form').on('submit',function(e){
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
    </script>
@endsection
