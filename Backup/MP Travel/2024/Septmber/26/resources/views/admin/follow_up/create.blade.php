@extends('admin.partials.header', ['active' => 'user'])

@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">

            <div class="mb-6 mb-xl-10">

                <div class="row g-3 align-items-center">

                    <div class="col">

                        <h1 class="ls-tight">{{ $page }} Create</h1>

                    </div>

                </div>

            </div>

            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

                <main class="container-fluid px-6 pb-10">

                    @php

                        $route = route('follow-up.store');

                        $method = 'POST';

                        if (isset($customer)) {
                            $route = route('follow-up.update', $customer->id);

                            $method = 'PUT';
                        }

                    @endphp

                    <form action="{{ $route }}" enctype="multipart/form-data" method="POST">

                        @method($method)

                        @csrf

                        <input type="hidden" name="id" value="{{ $customer->id ?? '' }}">

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

                            <div class="col-md-2"> <label for="Leads" class="form-label">Related To <span
                                        class="error_span">*</span></label></div>

                            <div class="col-md-4 col-xl-4">

                                <select name="lead_id" id="lead_id" class="form-control type_id">

                                    <option value="">Select Leads</option>

                                    @foreach ($leads as $lead)
                                        <option value="{{ $lead->id }}"
                                            {{ old('lead_id') == $lead->id ? 'selected' : '' }}>{{ $lead->lead_id }}</option>
                                    @endforeach

                                </select>

                                @error('lead_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label for="FollowupType" class="form-label">Followup Type<span
                                        class="error_span">*</span></label></div>

                            <div class="col-md-4 col-xl-4">

                                <select name="followup_type" id="" class="form-control followup_type">

                                    <option value="">Select type</option>

                                    <option value="1" {{ old('followup_type') == 1 ? 'selected' : '' }}>New Followup
                                    </option>

                                    <option value="2" {{ old('followup_type') == 2 ? 'selected' : '' }}>Existing
                                        Followup</option>

                                </select>

                                @error('followup_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">File reference</label></div>

                            <div class="col-md-4 col-xl-4">

                                <input class="form-control file" type="file" id="reference_file" name="reference_file">

                            </div>

                        </div>

                        <div class="">
                            <div
                                class="row align-items-center g-3 mt-6 existing {{ old('followup_type') != 2 ? 'd-none' : '' }}">
                                <div class="col-md-2"><label class="form-label mb-0">Select Existing Followup</label></div>

                                <div class="col-md-4 col-xl-4">

                                    <select name="followup" id="Followup" class="form-control followup">

                                        <option value="">Select</option>

                                        @foreach ($followUpList as $follow)
                                            <option value="{{ $follow->id }}"
                                                {{ old('followup') == $follow->id ? 'selected' : '' }}>
                                                {{ $follow->event_name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Subject<span
                                            class="error_span">*</span></label></div>

                                <div class="col-md-4 col-xl-4">

                                    <input type="text" class="form-control subject" name="subject" id="Subject"
                                        placeholder="" value="{{ old('subject') }}">

                                    @error('subject')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Start Date<span
                                            class="error_span">*</span></label></div>

                                <div class="col-md-4 col-xl-4">

                                    <input type="date" class="form-control start_date" name="start_date" id="start_date"
                                        placeholder="" value="{{ old('start_date', date('Y-m-d')) }}"
                                        min="{{ date('Y-m-d') }}">

                                    @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>



                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">End Date<span
                                            class="error_span">*</span></label></div>

                                <div class="col-md-4 col-xl-4">

                                    <input type="date" class="form-control end_date" name="end_date" id="end_date"
                                        placeholder="" value="{{ old('end_date', date('Y-m-d')) }}"
                                        min="{{ date('Y-m-d') }}">

                                    @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>

                                <div class="col-md-4 col-xl-4">

                                    <textarea name="remarks" id="Remarks" rows="3" class="form-control">{{ old('file_details') }}</textarea>

                                    @error('file_details')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Assigned To<span
                                                class="error_span">*</span></label></div>

                                    <div class="col-md-4 col-xl-4">

                                        <select name="assigned_to[]" class="form-control js-example-basic-single"
                                            id="assigned_to" multiple>

                                            <option value="">Select Assignee</option>

                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ in_array($user->id, old('assigned_to') ?? []) ? 'selected' : '' }}>

                                                    {{ $user->name }}</option>
                                            @endforeach

                                        </select>

                                        @error('assigned_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        <hr class="my-6">

                        <div class="d-flexjustify-content-end gap-2">

                            <a href="{{ route('follow-up.index') }}" class="btn btn-sm btn-neutral">Cancel</a>

                            <button type="submit" class="btn btn-sm btn-dark" id="follow_up_create_form">Save</button>

                        </div>

                    </form>

                </main>

            </div>

        </main>

    </div>
@endsection

@section('script')
    <script>
        $('form').on('submit', function(e) {
            $('#follow_up_create_form').html('<i class="fa fa-spinner fa-spin"></i>')
        })
        $(document).ready(function(e) {

            $('.type_id').select2();
            $('#assigned_to').select2();

        })

        $(document).on('change', '.followup_type', function() {

            if ($(this).val() == 1) {

                $('.existing').addClass('d-none');

                $('.new').removeClass('d-none');

                $('#assigned_to').select2();

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
