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
                                            {{ old('lead_id') == $lead->id ? 'selected' : '' }}>Lead - {{ $lead->id }}
                                        </option>
                                    @endforeach

                                </select>

                                @error('lead_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                        <div class="">

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Subject<span
                                            class="error_span">*</span></label></div>

                                <div class="col-md-4 col-xl-4">

                                    <input type="text" class="form-control subject" name="subject" id="Subject"
                                        placeholder="Enter Follow up" value="{{ old('subject') }}">

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
                                        placeholder="" value="{{ old('end_date', date('Y-m-d', strtotime('+1 day'))) }}"
                                        min="{{ date('Y-m-d') }}">

                                    @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>

                                <div class="col-md-4 col-xl-4">

                                    <textarea name="remarks" id="Remarks" rows="3" class="form-control" placeholder="Enter Remarks">{{ old('remarks') }}</textarea>

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
                                        @if (Auth()->user()->role_if == '1')
                                            <select name="assigned_to[]" class="form-control js-example-basic-single"
                                                id="assigned_to" multiple>

                                                <option value="">Select Assignee</option>

                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ in_array($user->id, old('assigned_to') ?? []) ? 'selected' : '' }}>

                                                        {{ $user->name }}</option>
                                                @endforeach

                                            </select>
                                        @else
                                            <select name="assigned_to[]" class="form-control js-example-basic-single read-only"
                                                id="assigned_to" multiple>

                                                <option value="">Select Assignee</option>

                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ in_array(Auth()->user()->id, old('assigned_to') ?? []) ? 'selected' : '' }}>

                                                        {{ $user->name }}</option>
                                                @endforeach

                                            </select>
                                        @endif

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
        $('#start_date').on('change',function(e){

            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if(start_date > end_date)
            {
                var nextDay = moment(start_date).add(1, 'days').format('YYYY-MM-DD');
                $('#end_date').val(nextDay);
                $('#end_date').setAttribute('min',nextDay);
            }
        })
    </script>
@endsection
