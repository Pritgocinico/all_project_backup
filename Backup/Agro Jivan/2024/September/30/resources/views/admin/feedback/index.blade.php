@extends('layouts.main_layout')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
    type="text/css" />
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">

                    <div id="kt_app_content" class=" flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="feedbackAjax(1)"
                                            placeholder="Search feedback" />
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
                                                            @foreach ($orderDistricts as $distrcit)
                                                                @if (isset($distrcit->districtDetail))
                                                                    <option value="{{ $distrcit->district }}">
                                                                        {{ $distrcit->districtDetail->district_name }}
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
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="feedbackAjax(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>

                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Order</h2>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="" class="form" action="#">
                                                        <div class="fv-row mb-10">
                                                            <label class="required fs-6 fw-semibold form-label mb-2">Select
                                                                Export Format:</label>
                                                            <select name="format" data-placeholder="Select a format"
                                                                id="export_format" data-hide-search="true"
                                                                class="form-select form-select-solid fw-bold">
                                                                <option value="">Select Format</option>
                                                                <option value="excel">Excel</option>
                                                                <option value="pdf">PDF</option>
                                                                <option value="csv">CSV</option>
                                                            </select>
                                                            <span id="export_format_error" class="text-danger"></span>
                                                        </div>
                                                        <div class="text-center">
                                                            <button type="reset" class="btn btn-light me-3"
                                                                data-bs-dismiss="modal">
                                                                Discard
                                                            </button>

                                                            <button type="button" class="btn btn-primary"
                                                                onclick="exportCSV()">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4" id="department_table_ajax">
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
    <div class="modal fade" id="add_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                            <section class="rating-widget">

                                <!-- Rating Stars Box -->
                                <div class="rating-stars text-center">
                                    <ul id="stars">
                                        <li class="star selected" title="Poor" data-value="1">
                                            <i class="fa fa-star fa-fw"></i>
                                        </li>
                                        <li class="star" title="Fair" data-value="2">
                                            <i class="fa fa-star fa-fw"></i>
                                        </li>
                                        <li class="star" title="Good" data-value="3">
                                            <i class="fa fa-star fa-fw"></i>
                                        </li>
                                        <li class="star" title="Excellent" data-value="4">
                                            <i class="fa fa-star fa-fw"></i>
                                        </li>
                                        <li class="star" title="WOW!!!" data-value="5">
                                            <i class="fa fa-star fa-fw"></i>
                                        </li>
                                    </ul>
                                </div>
                            </section>
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
    <input type="hidden" id="feedback_role_id" value="{{ Auth::user()->role_id }}">
@endsection
@section('page')
    <script src="{{ asset('public/assets/plugins/custom/rate/jquery.rateyo.min.js') }}"></script>
    <script>
        var role = $('#feedback_role_id').val();
        var exportFile = "{{ route('sale-order-feedback-ajax-export') }}";
        var subDistrictData = "{{route('get-sub-district-order')}}"
        if (role == 1) {
            exportFile = "{{ route('order-feedback-ajax-export') }}";
        }

        function exportCSV() {
            var format = $('#export_format').val();
            $('#export_format_error').html('');
            if (format == "") {
                $('#export_format_error').html('Please select format.');
                return false;
            }
            var search = $('#search_data').val();
            var district = $('#order_district').val();
            var sub_district = $('#order_sub_district').val();
            window.open(exportFile + '?format=' + format + '&search=' + search + "&district=" + district +
                "&sub_district=" + sub_district, '_blank');
        }
        $(document).ready(function(e) {
            feedbackAjax(1);
        })

        function feedbackAjax(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('order-feedback-ajax') }}",
                data: {
                    page: page,
                    search: $('#search_data').val(),
                    district: $('#order_district').val(),
                    sub_district: $('#order_sub_district').val(),
                },
                success: function(res) {
                    $('#department_table_ajax').html('');
                    $('#department_table_ajax').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip()
                }
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            feedbackAjax(page);
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
                    $('#order_feedback').val(res.order_description)
                    var stars = $(this).parent().children('li.star');
                    for (i = 0; i < res.rating; i++) {
                        $(stars[i]).addClass('selected');
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
                    feedbackAjax(1)
                    $('#add_department_modal').modal('hide');
                },
                error: function(xhr, status, error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {

            $('#stars li').on('mouseover', function() {
                var onStar = parseInt($(this).data('value'), 10);
                $(this).parent().children('li.star').each(function(e) {
                    if (e < onStar) {
                        $(this).addClass('hover');
                    } else {
                        $(this).removeClass('hover');
                    }
                });

            }).on('mouseout', function() {
                $(this).parent().children('li.star').each(function(e) {
                    $(this).removeClass('hover');
                });
            });

            $('#stars li').on('click', function() {
                var onStar = parseInt($(this).data('value'), 10);
                var stars = $(this).parent().children('li.star');

                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('selected');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                }
                var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                $('#rating').val(ratingValue)

            });


        });

        function getSubDistrictDetail() {
            var district = $('#order_district').val()
            $.ajax({
                method: "get",
                url: subDistrictData,
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
