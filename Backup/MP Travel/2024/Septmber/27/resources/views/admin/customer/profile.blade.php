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
                        @foreach ($customerDetail->servicePreferenceTagDetail as $key => $servicePreference)
                            <div class="row g-3 align-items-center mt-6 row-items-{{ $servicePreference->id }}">
                                <input type="hidden" name="service_preference_tag_id[]"
                                    class="row-item"value="{{ $servicePreference->id }}">
                                <div class="col-md-3"><label class="form-label mb-0">Preference Tagging Service:<span class="error_span">*</span></label>
                                </div>
                                <div class="col-md-1">
                                    <input type="checkbox" id="preference_check" name="preference_check[]"
                                        class="form-check pre-check-1 " value="1" disabled {{$servicePreference->status == 1 ? "checked" :""}}>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" id="preference_id" name="preference_id[]"
                                        onchange="checkedBox(1)" data-id="1" required>
                                        <option value="">Select Service Preference</option>
                                        @foreach ($servicePreferenceList as $preference)
                                            <option value="{{ $preference->id }}"
                                                {{ $servicePreference->service_preference_id == $preference->id ? 'selected' : '' }}>
                                                {{ $preference->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <a href="javascript:void(0)" onclick="removeDivTag('remove',{{$servicePreference->id}})"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                                <div class="col-md-3 text-end">
                                    @if ($key == 0)
                                        <a href="javascript:void(0)"  class="btn btn-sm btn-dark" id="add_more_button">Add More</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="row align-items-center g-3 mt-6 add-more-items-1">
                            <input type="hidden" name="service_preference_tag_id[]" class="row-item"value="">
                            <div class="col-md-3"><label class="form-label mb-0">Preference Tagging Service:<span class="error_span">*</span></label></div>
                            <div class="col-md-1">
                                <input type="checkbox" id="preference_check" name="preference_check[]"
                                    class="form-check pre-check-1" value="1">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select preference_id-1" id="preference_id" name="preference_id[]"
                                    onchange="checkedBox(1)" data-id="1" required>
                                    <option value="">Select Service Preference</option>
                                    @foreach ($servicePreferenceList as $preference)
                                        <option value="{{ $preference->id }}">{{ $preference->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 text-end">
                                @if(count($customerDetail->servicePreferenceTagDetail) == 0)
                                <a href="javascript:void(0)"  class="btn btn-sm btn-dark" id="add_more_button">Add More</a>
                                @endif
                            </div>
                        </div>
                        <div id="service_preference_add_more"></div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Customer Type:</label></div>
                            <div class="col-md-4">
                                <select class="form-select" id="customer_type" name="customer_type">
                                    <option value="">Select Customer Type</option>
                                    <option value="budget" {{$customerDetail->customer_type == "budget" ? "selected" : ""}}>Budgeted</option>
                                    <option value="premium" {{$customerDetail->customer_type == "premium" ? "selected" : ""}}>Premium</option>
                                </select>
                            </div>
                            <div class="col-md-2 budget_div {{$customerDetail->customer_type !== "budget" ? "d-none" : ""}}">
                                <label>Budget Amount</label>
                            </div>
                            <div class="col-md-4 budget_div {{$customerDetail->customer_type !== "budget" ? "d-none" : ""}}">
                                <input type="number" name="budget_amount" id="budget_amount" placeholder="Enter Budget Amount" value="{{$customerDetail->budget_amount ?? 0}}" class="form-control">
                            </div>
                            <div class="col-md-2 premium_div {{$customerDetail->customer_type !== "premium" ? "d-none" : ""}}">
                                <label>Premium Amount</label>
                            </div>
                            <div class="col-md-4 premium_div {{$customerDetail->customer_type !== "premium" ? "d-none" : ""}}">
                                <input type="number" name="premium_amount" id="premium_amount" placeholder="Enter Premium Amount" value="{{$customerDetail->premium_amount ?? 0}}" class="form-control">
                            </div>
                        </div>
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
            var number = 1;
            $('#add_more_button').on('click', function(e) {
                number++;
                var html = `<div class="row align-items-center g-3 mt-6 add-more-items-${number}">
                    <input type="hidden" name="service_preference_tag_id[]" value="">
                            <div class="col-md-3"></div>
                            <div class="col-md-1">
                                <input type="checkbox" id="preference_check" name="preference_check[]" class="form-check pre-check-${i}" value="1">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select preference_id-${i}" name="preference_id[]" data-id=${i} onchange=checkedBox(${i}) required>
                                    <option value="">Select Service Preference</option>
                                    @foreach ($servicePreferenceList as $preference)
                                        <option value="{{ $preference->id }}">{{ $preference->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0)" onclick="removeDivTag('button',${number})"><i class="fa-solid fa-trash-can"></i></a>
                            </div>
                        </div>`;
                $('#service_preference_add_more').append(html);
                i = i + 1;
            });

        })

        function checkedBox(i) {
            var type = $(`.preference_id-${i}`).val();
            $('.pre-check-' + i).attr('checked', true);
            if(type == ""){
                $('.pre-check-' + i).attr('checked', false);
            }
        }
        $('.remove-button').on('click', function(e) {
            var type = $(this).data('type');
            var id = $(this).data('id');

            alert(id);

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this service preference tag for this customer?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('service-preference-tag-delete') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id,
                            'customer_id': `{{ $customerDetail->id }}`,
                        },
                        success: function(data) {
                            $(`.row-items-${id}`).remove();
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        });
        function removeDivTag(type,id){
            if (type == "button") {
                $(`.add-more-items-${id}`).remove();
                return 1;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this service preference tag for this customer?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('service-preference-tag-delete') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            'id': id,
                            'customer_id': `{{ $customerDetail->id }}`,
                        },
                        success: function(data) {
                            $(`.row-items-${id}`).remove();
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }
        $('#customer_type').on('change',function(e){
            var type = $(this).val();
            if (type == "budget") {
                $('.budget_div').removeClass('d-none');
                $('.premium_div').addClass('d-none');
            }
            if (type == "premium") {
                $('.premium_div').removeClass('d-none');
                $('.budget_div').addClass('d-none');
            }
        })
    </script>
@endsection
