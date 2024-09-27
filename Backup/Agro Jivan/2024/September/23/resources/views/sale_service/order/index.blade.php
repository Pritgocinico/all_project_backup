@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="allOrderAjax(1)"
                                            placeholder="Search order" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu custom-close w-300px w-md-325px"
                                                data-kt-menu="true">
                                                <div class="px-7 py-5 d-flex align-items-center justify-content-between">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                    <a href="#" class="close-btn-filter"><i
                                                            class="fa fa-close"></i></a>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">District:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="order_district" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true" onchange="getSubDistrictDetail()">
                                                            <option value="">Select District</option>
                                                            @foreach ($orderDistricts as $districts)
                                                                @if (isset($districts->districtDetail))
                                                                    <option value="{{ $districts->district }}">
                                                                        {{ $districts->districtDetail->district_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Sub District:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="order_sub_district" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true">
                                                            <option value="">Select Sub District</option>
                                                            @foreach ($orderSubDistricts as $subDistricts)
                                                                @if (isset($subDistricts->subDistrictDetail))
                                                                    <option value="{{ $subDistricts->sub_district }}">
                                                                        {{ $subDistricts->subDistrictDetail->sub_district_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetOrderForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="allOrderAjax(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body py-4 table-responsive" id="manual_order_table_ajax">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>
    </div>
    </div>

    </body>
    <div class="modal fade" id="add_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Write Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="feedback_form" name="feedback_form">

                        <input type="hidden" name="order_id" id="order_id">
                        <div class="row">
                            <label for="rating">Rating:</label>
                            <div id="star-rating"></div>
                            <input type="hidden" name="rating" id="rating">
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Feedback</label>
                            <textarea name="order_feedback" id="order_feedback" rows="5" cols="5" class="form-control"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addFeedback()">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page')
    <script src="{{ asset('public/assets/plugins/custom/rate/jquery.rateyo.min.js') }}"></script>
    <script>
        $(function() {
            $("#star-rating").rateYo({
                rating: 0,
                onSet: function(rating, rateYoInstance) {
                    $("#rating").val(rating);
                }
            });
        });
        $(document).ready(function(e) {
            allOrderAjax(1);
        })

        function resetOrderForm() {
            $('#search_data').val('');
            $('#order_district').val('');
            $('#order_sub_district').val('');
            allOrderAjax(1)
        }

        function allOrderAjax(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('delivered-order-ajax') }}",
                data: {
                    page: page,
                    search: $('#search_data').val(),
                    district: $('#order_district').val(),
                    sub_district: $('#order_sub_district').val(),
                },
                success: function(res) {
                    $('#manual_order_table_ajax').html('');
                    $('#manual_order_table_ajax').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip()
                }
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            allOrderAjax(page);
        });

        function openFeedbackModal(id) {
            $.ajax({
                method: 'get',
                url: "{{ route('feedback-detail') }}",
                data: {
                    order_id: id,
                },
                success: function(res) {
                    $('#order_id').val(id);
                    if (res !== "") {
                        $("#star-rating").rateYo({
                            rating: res.rating,
                            onSet: function(rating, rateYoInstance) {
                                $("#rating").val(rating);
                            }
                        });
                        $("#rating").val(res.rating);
                        $('#order_feedback').val(res.order_description)
                    }
                    $('#add_department_modal').modal('show')
                }
            })
        }

        function addFeedback() {
            var formData = $('#feedback_form').serialize();
            $.ajax({
                url: '{{ route('add-order-feedback') }}',
                method: 'get',
                data: formData,

                success: function(res) {
                    toastr.success(res.message);
                    allOrderAjax(1)
                    $('#add_department_modal').modal('hide');
                },
                error: function(xhr, status, error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        }

        function getSubDistrictDetail() {
            var district = $('#order_district').val()
            $.ajax({
                method: "get",
                url: "{{route('get-sub-district-order')}}",
                data: {
                    district: district
                },
                success: function(res) {
                    var html = "<option value=''>Select Sub District</option>";
                    $.each(res, function(i, v) {
                        html += "<option value='" + v.sub_district + "'>" + v.sub_district_detail
                            .sub_district_name + "</option>"
                    })
                    $('#order_sub_district').html('');
                    $('#order_sub_district').html(html);
                }
            })
        }
    </script>
@endsection
