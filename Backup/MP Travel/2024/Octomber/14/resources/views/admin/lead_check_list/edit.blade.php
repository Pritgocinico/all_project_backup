@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create {{ $page }}</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('lead-checklist.update', $checkList->id) }}" enctype="multipart/form-data"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Lead Type<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="lead_type" id="lead_type" class="form-select">
                                        <option value="">Select Lead Type</option>
                                        <option value="investments"
                                            {{ $checkList->type == 'investments' ? 'selected' : '' }}>Investments</option>
                                        <option value="general insurance"
                                            {{ $checkList->type == 'general insurance' ? 'selected' : '' }}>General
                                            Insurance</option>
                                        <option value="travel" {{ $checkList->type == 'travel' ? 'selected' : '' }}>Travel
                                        </option>
                                    </select>
                                    @error('lead_type')
                                        <div class="text-danger required-msg">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="javascript:void(0)" class="btn btn-primary btn-sm" id="add_more_button">Add
                                More</a>
                        </div>
                        @forelse ($checkList->leadCheckListItems as $key=>$item)
                            <div class="row align-items-center g-3 mt-6" id="exist_add_more_option_{{ $item->id }}">
                                <input type="hidden" name="check_list_item_id[]" value="{{ $item->id }}">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Check List Item<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="name[]" class="form-control" placeholder="Enter Name"
                                            value="{{ $item->name }}">
                                        @error('name.' . $key)
                                            <div class="text-danger required-msg">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm"
                                            onclick="removeExistList({{ $item->id }})" id="remove-button">Remove</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <input type="hidden" name="check_list_item_id[]">
                            <div class="row align-items-center g-3 mt-6" id="add_more_option_0">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Check List Item<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="name[]" class="form-control" placeholder="Enter Name">
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-3">
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm"
                                            onclick="removeCheckList(0)" id="remove-button">Remove</a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                        <div id="add_more_check_list"></div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('lead-checklist.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
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
        $('form').on('submit', function(e) {
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
        var no = 0;
        $('#add_more_button').on('click', function(e) {
            no = no + 1;
            var html = `<div id="add_more_option_${no}">
                <input type="hidden" name="check_list_item_id[]">
            <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Lead Check List Item<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="name[]" class="form-control" placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-3">
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick=removeCheckList(${no}) id="remove_button" data-number=${no}>Remove</a>
                                </div>
            </div>
            </div>
            </div>`;
            $('#add_more_check_list').append(html);
        });

        function removeCheckList(no) {
            $(`#add_more_option_${no}`).remove();
        }

        function removeExistList(id) {
            swal.fire({
                'title': 'Are you sure?',
                'text': "You won't be able to revert this!",
                'icon': 'warning',
                'showCancelButton': true,
                'confirmButtonColor': '#3085d6',
                'cancelButtonColor': '#d33',
                'confirmButtonText': 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('lead-checklist-remove') }}",
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            $('#exist_add_more_option_' + id).remove();
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            })
        }
    </script>
@endsection
