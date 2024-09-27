@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{ $page }} Update</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{route('follow-up.update', $followUpEvent->id)}}" enctype="multipart/form-data" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $followUpEvent->id ?? '' }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Select Type</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="type" id="type" class="form-control type read-only">
                                    <option value="1">Lead</option>
                                    <option value="2">Task</option>
                                </select>
                                @error('type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"> <label for="Leads" class="form-label">Related To </label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="lead_id" id="lead_id" class="form-control type_id">
                                    <option value="">Select Leads</option>
                                    @foreach ($leads as $lead)
                                        <option value="{{ $lead->id }}" {{ $followUpEvent->lead_id == $lead->id ? "selected" :"" }}>{{ $lead->lead_id }}</option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label for="FollowupType" class="form-label">Followup Type</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="followup_type" id="" class="form-control followup_type">
                                    <option value="0">Select type</option>
                                    <option value="1" {{ $followUpEvent->followup_type == 1 ? "selected" :"" }}>New Followup</option>
                                    <option value="2" {{ $followUpEvent->followup_type == 2 ? "selected" :"" }}>Existing Followup</option>
                                </select>
                                @error('followup_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">File reference</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input class="form-control file" type="file" id="reference_file"
                                    name="reference_file">
                            </div>
                        </div>
                        <div class="@if($followUpEvent->followup_type == 2) d-none @endif new">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Subject</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" class="form-control subject" name="subject" id="Subject"
                                        placeholder="" value="{{ $followUpEvent->event_name }}">
                                    @error('subject')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Start Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" class="form-control start_date" name="start_date" id="start_date"
                                        placeholder="" value="{{ $followUpEvent->event_start }}">
                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">End Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" class="form-control end_date" name="end_date" id="end_date"
                                        placeholder="" value="{{ $followUpEvent->event_end }}">
                                    @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="remarks" id="Remarks" rows="3" class="form-control">{{ $followUpEvent->remarks}}</textarea>
                                    @error('file_details')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="assigned_to[]" class="form-control js-example-basic-single"
                                            id="assigned_to" multiple>
                                            <option value="">Select Assignee</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ in_array($user->id, $followUpMember) ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @error('assigned_to')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="existing @if($followUpEvent->followup_type == 1) d-none @endif">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Select Existing Followup</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="followup" id="Followup" class="form-control followup">
                                        <option value="">Select</option>
                                        @foreach ($followUpList as $follow)
                                            <option value="{{ $follow->id }}">{{ $follow->event_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Subject</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" class="form-control" name="exist_subject" id="subject"
                                        placeholder="" value="{{ old('exist_subject') }}">
                                    @error('exist_subject')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Start Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="exist_start_date" id="exist_start_date" class="form-control">
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">End Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="exist_end_date" id="exist_end_date" class="form-control">
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="exist_remarks" id="exist_remarks" rows="3" class="form-control">{{ old('file_details') }}</textarea>
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Followup Status</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="status" id="" class="status form-control">
                                        <option value="1">Completed</option>
                                        <option value="2">Extend</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('follow-up.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            $('.type_id').select2();
            $('#assigned_to').select2();
        })
        $(document).on('change', '.followup_type', function() {
            if ($(this).val() == 1) {
                $('.existing').addClass('d-none');
                $('.new').removeClass('d-none');
            } else if ($(this).val() == 2) {
                var type = $('.type').val();
                $('.new').addClass('d-none');
                $('.existing').removeClass('d-none');
            } else {
                $('.new').addClass('d-none');
                $('.existing').addClass('d-none');
            }
        });
    </script>
@endsection
