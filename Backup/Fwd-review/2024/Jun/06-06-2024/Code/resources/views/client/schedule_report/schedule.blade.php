@extends('client.layouts.app')
<style>
    .chosen-container {
        width: 100% !important;
    }

    .table-condensed thead {
        display: none !important;
    }
</style>
<link rel="stylesheet" href="{{ url('/') }}/assets/Css/select2.min.css">
@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-3">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Schedule Reports</li>
                </ol>
            </nav>
        </div>
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">Schedule Reports</div>
            </div>
        </div>
        @php $emailArray = isset($schedule) ?explode(',',$schedule->add_recipient) : []; @endphp
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="schedule_report_div_box">
                            <h4>Report Setting</h4>
                            <form action="{{ route('client.report.schedule.create') }}" method="post" class="row g-3">
                                @csrf
                                <input type="hidden" name="frequency" id="frequency_data" value="">
                                <input type="hidden" name="daily_interval" id="frs_daily_interval_data" value="">
                                <input type="hidden" name="weekly_interval" id="rs_weekly_interval_data" value="">
                                <input type="hidden" name="monthly_date" id="monthly_date_data" value="">
                                <div class="col-md-12 form-floating mt-4">
                                    <select name="schedule" class="form-control" id="schedule"
                                        onchange="customeDetailModel()">
                                        <option selected="selected" value="Daily">
                                            Daily</option>
                                        <option disabled="disabled" value="or">or</option>
                                        <option value="custom">Change schedule...</option>
                                    </select>
                                    <label for="Client" class="form-label">Schedule *</label>
                                    @if ($errors->has('schedule'))
                                        <span class="text-danger">{{ $errors->first('schedule') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select name="timeframe_cover_date" class="form-control" id="timeframe_cover_date">
                                        <option value="12">12</option>
                                        <option value="11">11</option>
                                        <option value="10">10</option>
                                        <option value="9">12</option>
                                        <option value="8">8</option>
                                        <option value="7">7</option>
                                        <option value="6">6</option>
                                        <option value="5">5</option>
                                        <option value="4">4</option>
                                        <option value="3" selected>3</option>
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                    </select>
                                    <label for="Client" class="form-label">Timeframe Covered *</label>
                                </div>
                                <div class="col-md-6 form-floating mt-4">
                                    <select name="timeframe_cover_type" class="form-control" id="timeframe_cover_type">
                                        <option value="days">day(s)</option>
                                        <option value="weeks">week(s)</option>
                                        <option value="months" selected>month(s)</option>
                                    </select>
                                    <label for="Client" class="form-label">Timeframe Covered *</label>
                                </div>
                                <div class="col-md-12 form-floating mt-4">
                                    <input type="checkbox" class="form-check-input" name="customize_report"
                                        id="customize_report" value="1" placeholder="" /> Customize report sections?
                                    @if ($errors->has('customize_report'))
                                        <span class="text-danger">{{ $errors->first('customize_report') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-12 form-floating mt-4" id="custom_sections" style="display: none">
                                    <div class="field custom_sections">
                                        <input name="report_schedule[]" value="location" type="checkbox" checked="checked">
                                        Map and Locations
                                    </div>
                                    <div class="field custom_sections">
                                        <input name="report_schedule[]" value="section_score" type="checkbox">
                                        Overall Score
                                    </div>
                                    <div class="field custom_sections">
                                        <input name="report_schedule[]" value="section_overview" type="checkbox"
                                            checked="checked"> Reputation Overview
                                    </div>
                                    <div class="field custom_sections">
                                        <input name="report_schedule[]" value="section_volume" type="checkbox"
                                            checked="checked"> Review Volume &amp; Distribution
                                    </div>
                                    <div class="field custom_sections">
                                        <input name="report_schedule[]" value="section_listings" type="checkbox"
                                            checked="checked"> Listings Strength
                                    </div>
                                    <div class="field custom_sections">
                                        <input name="report_schedule[]" value="section_reviews" type="checkbox"
                                            checked="checked"> New Reviews
                                    </div>
                                </div>
                                <div class="" id="add_recipient_detail">

                                    @forelse ($emailArray as $key => $email)
                                        <div class="row receipt-default-{{ $key }}">
                                            <div class="col-md-10 form-floating mt-4">
                                                <input type="text" class="form-control" name="add_recipient[]"
                                                    value="{{ $email }}" placeholder="" />
                                                <label for="Client" class="form-label">Add Recipients *</label>
                                            </div>
                                            @if ($key == 0)
                                                <div class="col-md-2">
                                                    <input type="button" class="btn btn-primary add_more_button_report"
                                                        id="add_more_button_report_button" value="Add" />
                                                </div>
                                            @else
                                                <div class="col-md-2">
                                                    <img src="{{ url('/') }}/assets/Images/delete.png" alt=""
                                                        class="ed_btn add_more_button_report"
                                                        onclick="removerAddMoreOption({{ $key }},'default')">
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="row receipt-default">
                                            <div class="col-md-10 form-floating">
                                                <input type="text" class="form-control" name="add_recipient[]"
                                                    value="" placeholder="" />
                                                <label for="Client" class="form-label">Add Recipients *</label>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="button" class="btn btn-primary add_more_button_report"
                                                    id="add_more_button_report_button" value="Add" />
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn gc_btn mt-3">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="schedule_report_div_box">
                            <div class="mail-header">
                                <h4>Title</h4>
                            </div>
                            <div class="email-settings">
                                <div class="mail-template-preview">
                                    <div class="mail-headers">
                                        <div class="mail-header send_on_weekends d-flex">
                                            <label>Send on weekends:</label>
                                            <select name="send_weekend" class="form-control" id="send_weekend">
                                                <option class="Yes"
                                                    @if (isset($emailTemplate) && $emailTemplate->send_weekend == 'yes') {{ 'selected' }} @endif>Yes
                                                </option>
                                                <option class="no"
                                                    @if (isset($emailTemplate) && $emailTemplate->send_weekend == 'no') {{ 'selected' }} @endif>No
                                                </option>
                                            </select>
                                            @if ($errors->has('schedule'))
                                                <span class="text-danger">{{ $errors->first('schedule') }}</span>
                                            @endif
                                        </div>
                                        <div class="mail-header from_name d-flex mt-3">
                                            <label>From name:</label>
                                            <input type="text" name="from_name_mail" id="from_name_mail"
                                                value="{{ isset($emailTemplate) ? $emailTemplate->from_name_mail : old('from_name_mail') }}"
                                                class="form-control">
                                        </div>
                                        <div class="mail-header reply_address mt-3 d-flex">
                                            <label>Reply-to:</label>
                                            <input type="text" name="reply_to" id="reply_to"
                                                value="{{ isset($emailTemplate) ? $emailTemplate->reply_to : old('reply_to') }}"
                                                class="form-control">
                                        </div>
                                        <div class="mail-header subject mt-3 d-flex">
                                            <label>Subject:</label>
                                            <input type="text" name="email_subject" id="email_subject"
                                                value="{{ isset($emailTemplate) ? $emailTemplate->email_subject : old('email_subject') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="mail-body body_html">
                                        <label>Description:- </label>
                                        <textarea cols="50" rows="5" name="email_description" id="email_description" value=""
                                            class="form-control">{{ isset($emailTemplate) ? $emailTemplate->email_description : old('email_description') }}</textarea>
                                    </div>
                                    <div class="mail-footer mt-3" id="mail-footer">
                                        <div class="controls float-end">
                                            <button class="btn btn-secondary add-test-recipient"
                                                onclick="updateEmailTemplate()" type="button">Send a Test</button>
                                        </div>
                                        &nbsp;
                                    </div>
                                </div>

                                <div class="explainer-text">This email gets sent out with each scheduled report.</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="schedule_report_div_box">
                            <span><b>Note:</b> We use placeholders for the following values in case they change: </span>
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <input type="text" value="[[profile_name]]" readonly class="form-control">
                                    <span>“Parthenon Plumbing Heating & Cooling: Home”</span>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <input type="text" value="[[report_title]]" readonly class="form-control">
                                    <span>the title of the report</span>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <input type="text" value="[[report_date]]" readonly class="form-control">
                                    <span>the date of the report</span>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <input type="text" value="[[report_url]]" readonly class="form-control">
                                    <span>the Web URL of the report</span>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <input type="text" value="[[brand_name]]" readonly class="form-control">
                                    <span>“ReviewLead”</span>
                                </div>
                                <div class="m-auto" style="width: 90%">
                                    <hr class="mt-3">
                                    <h5 class="mt-3 text-center">Unschedule Report</h5>
                                    <button class="btn gc_btn ms-4" onclick="unscheduleReport()">Stop The Report</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="RequestReportModal" tabindex="-1" aria-labelledby="RequestReportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Repeat <a href="#" title="Cancel" alt="Cancel"></a> </h1>
                </div>
                <div class="modal-body">
                    <div class="rs_dialog_content" style="width: auto;">
                        <div class="row">
                            <div class="col-md-12 form-floating mt-3">
                                <select class="form-select" name="frequency" onchange="dateSelectOption()"
                                    id="frequency">
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Yearly">Yearly</option>
                                </select>
                                <label for="Client" class="form-label">Frequency *</label>
                            </div>
                        </div>

                        <div class="daily_options freq_option_section mt-3" style="display: block;">
                            <p class="d-flex"> Every <input type="text" class="form-control mx-2"
                                    name="rs_daily_interval" class="form-control" value="1" size="2"
                                    id="rs_daily_interval"> day(s)
                            </p>
                        </div>
                        <div class="weekly_options freq_option_section form-floating mt-3 d-none">
                            <select class="form-control w-100" name="rs_weekly_interval" id="rs_weekly_interval"
                                multiple>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                        </div>
                        <div class="monthly_options freq_option_section mt-3 d-none form-floating">
                            <input type="text" id="datepicker" class="form-control" name="monthly_date" multiple>
                            <label for="Client" class="form-label">Month Day *</label>
                        </div>
                        <p class="rs_summary mt-3">Summary: <span id="daily_text_change">Daily</span> </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="controls">
                        <input type="button" data-wrapper-class="ui-recurring-select" class="rs_save btn gc_btn"
                            value="OK">
                        <input type="button" data-wrapper-class="ui-recurring-select" class="rs_cancel btn btn-danger"
                            value="Cancel">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="RequestReportModal1" tabindex="-1" aria-labelledby="RequestReportModal1Label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="title" id="label-modal-mail-recipient">Send a Test</h3>
                    <a class="close" data-bs-dismiss="modal" aria-hidden="true" data-bs-dismiss="modal" role="button"><i class="fa fa-close"></i></a>
                </div>
                <div class="modal-body">
                    <div class="rs_dialog_content" style="width: auto;">
                        <div class="row">
                            <div class="col-md-12 form-floating mt-3">
                                <input type="email" class="form-control mx-2" name="email_address"
                                    class="form-control" placeholder="" id="email_address">
                                <label for="Client" class="form-label">Email Address *</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-floating mt-3">
                                <input type="text" class="form-control mx-2" name="first_name" class="form-control"
                                    placeholder="" id="first_name">
                                <label for="Client" class="form-label">First Name *</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-floating mt-3">
                                <input type="text" class="form-control mx-2" name="last_name" class="form-control"
                                    placeholder="" id="last_name">
                                <label for="Client" class="form-label">Last Name *</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="controls">
                        <input type="button" data-wrapper-class="ui-recurring-select"
                            class="rs_save btn gc_btn send_test_mail" value="Send Test">
                        <input type="button" data-wrapper-class="ui-recurring-select" class="rs_cancel btn btn-danger"
                            value="Cancel" data-bs-dismiss="modal">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/jquery-ui.css">
    <link rel="stylesheet" href="{{ url('/') }}/assets/Css/chosen.css">
    <script src="{{ url('/') }}/assets/JS/jquery-ui.min.js"></script>
    <script src="{{ url('/') }}/assets/JS/chosen.js"></script>
    <script>
        $(document).ready(function(e) {
            var currentDate = new Date();
            var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            var lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            $('#rs_weekly_interval').chosen().change(function() {
                var selectedValues = $(this).val();
                var html = 'Weekly on ';
                $.each(selectedValues, function(index, value) {
                    html += value;
                    if (selectedValues.length - 1 != index) {
                        html += " and ";
                    }
                });
                $('#daily_text_change').html(html)
            });
            $('[data-bs-toggle="tooltip"]').tooltip();
            $("#datepicker").datepicker({
                multidate: true,
                format: 'dd',
                startDate: firstDayOfMonth,
                endDate: lastDayOfMonth,

            }).on('changeDate', function(e) {
                var selectedDates = e.dates;
                var html = "Monthly on the "
                $.each(selectedDates, function(index, value) {
                    var day = value.getDate();
                    var formattedDay = (day < 10 ? '0' : '') + day;
                    html += formattedDay;
                    if (value.length - 1 != index) {
                        html += " and ";
                    }
                });
                html += "days of the month";
                $('#daily_text_change').html(html)
            });
        })
        $('#customize_report').on('change', function(e) {
            if ($(this).is(':checked')) {
                $('#custom_sections').show();
            } else {
                $('#custom_sections').hide();
            }
        });
        var i = 0;
        $('#add_more_button_report_button').on('click', function(e) {
            i++;
            var html = '';
            html += '<div class="receipt-' + i + '">';
            html += '<div class="row ">';
            html += '<div class="col-md-10 form-floating mt-4">';
            html += '<input type="text" class="form-control" name="add_recipient[]" placeholder="" />';
            html += '<label for="Client" class="form-label">Add Recipients *</label>';
            html += '</div>';
            html += '<div class="col-md-2">';
            html +=
                `<img src="{{ url('/') }}/assets/Images/delete.png" alt="" class="ed_btn add_more_button_report" onclick="removerAddMoreOption(` +
                i + `,'add')">`;
            html += '</div>';
            html += '</div>';
            html += '</div>';
            $('#add_recipient_detail').append(html)
        });

        function removerAddMoreOption(id, type) {
            if (type == 'add') {
                $('.receipt-' + id).remove();
            } else {
                $('.receipt-default-' + id).remove();
            }
        }

        function customeDetailModel() {
            var schedule = $('#schedule').val();
            if (schedule == 'custom') {
                $('#RequestReportModal').modal('show');
            }
        }

        function updateEmailTemplate() {
            $.ajax({
                method: "post",
                url: "{{ route('update.email.template') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    send_weekend: $('#send_weekend').val(),
                    from_name_mail: $('#from_name_mail').val(),
                    reply_to: $('#reply_to').val(),
                    email_subject: $('#email_subject').val(),
                    email_description: $('#email_description').val(),
                },
                success: function(data) {
                    $('#RequestReportModal1').modal('show');
                }
            })
        }

        function dateSelectOption() {
            var type = $('#frequency').val()
            $('.weekly_options').addClass('d-none')
            $('.monthly_options').addClass('d-none')
            if (type == "Weekly") {
                $('.weekly_options').removeClass('d-none')
            }
            if (type == "Monthly") {
                $('.monthly_options').removeClass('d-none')
            }
            if (type == "Daily") {
                $('#daily_text_change').html('Daily')
            }
            if (type == "Yearly") {
                $('#daily_text_change').html('Yearly')
            }
        }
        $('.rs_save').on('click', function(e) {
            $('#frequency_data').val($('#frequency').val());
            $('#frs_daily_interval_data').val($('#rs_daily_interval').val());
            $('#rs_weekly_interval_data').val($('#rs_weekly_interval').val());
            $('#monthly_date_data').val($('#datepicker').val());
            $('#RequestReportModal').modal('hide');
        })
        $('.send_test_mail').on('click', function(e) {
            $(this).attr('disabled', 'disabled')
            $.ajax({
                method: "get",
                url: "{{ route('sent.test.email') }}",
                data: {
                    email_address: $('#email_address').val(),
                    first_name: $('#first_name').val(),
                    last_name: $('#last_name').val(),
                },
                success: function(data) {
                    $(this).attr('disabled', '');
                    $('#RequestReportModal1').modal('hide');
                },
                error: function() {
                    $(this).attr('disabled', '');
                }
            })
        })

        function unscheduleReport() {
            $.ajax({
                method: "get",
                url: "{{ route('client.report.schedule.stop') }}",
                success: function(data) {
                    toastr.error(data.message);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            })
        }
    </script>
@endsection
