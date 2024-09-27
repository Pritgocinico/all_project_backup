@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Customer Preference Tagging Service</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('customer.update-profile') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        
                        <input type="hidden" name="customer_id" value="{{ $customerDetail->id }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Customer Id:</label></div>
                            <div class="col-md-4">{{ $customerDetail->customer_id }}</div>
                            <div class="col-md-2"><label class="form-label mb-0">Customer Name:</label></div>
                            <div class="col-md-4">{{ $customerDetail->name }}</div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Customer Email:</label></div>
                            <div class="col-md-4">{{ $customerDetail->email }}</div>
                            <div class="col-md-2"><label class="form-label mb-0">Phone Number:</label></div>
                            <div class="col-md-4">{{ $customerDetail->mobile_number }}</div>
                        </div>
                        <hr class="my-6">
                        <div class="row g-3 align-items-center">
                            @foreach ($customerDetail->servicePreferenceTagDetail as $servicePreference)
                                <input type="hidden" name="service_prefrence_tag_id[]" value="{{$servicePreference->id}}">
                                <div class="col-md-3"><label class="form-label mb-0">Preference Tagging Service:</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="checkbox" id="preference_check" name="preference_check"
                                        class="form-check pre-check-1 " value="1" disbled checked>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="preference_id" name="preference_id[]"
                                        onchange="checkedBox(1)" data-id="1">
                                        <option value="">Select Service Preference</option>
                                        @foreach ($servicePreferenceList as $preference)
                                            <option value="{{ $preference->id }}" {{$servicePreference->service_preference_id == $preference->id ? "selected" :""}}>{{ $preference->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    
                                </div>
                            @endforeach
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-3"><label class="form-label mb-0">Preference Tagging Service:</label></div>
                            <div class="col-md-1">
                                <input type="checkbox" id="preference_check" name="preference_check"
                                    class="form-check pre-check-1" value="1">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="preference_id" name="preference_id[]"
                                    onchange="checkedBox(1)" data-id="1">
                                    <option value="">Select Service Preference</option>
                                    @foreach ($servicePreferenceList as $preference)
                                        <option value="{{ $preference->id }}">{{ $preference->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="javascript:void(0)" class="btn btn-sm btn-dark" id="add_more_button">Add More</a>
                            </div>
                        </div>
                        <div id="service_preference_add_more"></div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('customer.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
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
        var i = 2;
        $(document).ready(function(e) {
            $('form').on('submit', function(e) {
                $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
            });
            $('#add_more_button').on('click', function(e) {
                var html = `<div class="row align-items-center g-3 mt-6">
                            <div class="col-md-3"></div>
                            <div class="col-md-1">
                                <input type="checkbox" id="preference_check" name="preference_check" class="form-check pre-check-${i}" value="1">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select preference_id" name="preference_id[]" data-id=${i} onchange=checkedBox(${i})>
                                    <option value="">Select Service Preference</option>
                                    @foreach ($servicePreferenceList as $preference)
                                        <option value="{{ $preference->id }}">{{ $preference->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 text-end">
                            </div>
                        </div>`;
                $('#service_preference_add_more').append(html);
                i = i + 1;
            });

        })

        function checkedBox(i) {
            $('.pre-check-' + i).attr('checked', true);
        }
    </script>
@endsection
