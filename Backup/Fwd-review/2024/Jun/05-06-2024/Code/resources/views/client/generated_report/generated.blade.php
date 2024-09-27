@extends('client.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-3">
                    <li class="breadcrumb-item"><a href="#">Reports</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Generated Reports</li>
                </ol>
            </nav>
        </div>
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">Generated Reports</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills gap-2" id="pills-tab" role="tablist">
                    <button type="button" class="btn gc_btn" data-bs-toggle="modal" data-bs-target="#RequestReportModal">
                        Generate New Report
                    </button>
                </ul>
            </div>
        </div>
        <div class="tab-content mt-3" id="pills-tabContent">
            <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="">
                <div class="card">
                    <div class="table-responsive p-3">
                        <table id="example" class="table rwd-table mb-0 review-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Created On</th>
                                    <th>Date Range</th>
                                    <th>Links</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report['name'] }}</td>
                                        <td>{{ date('Y-m-d',strtotime($report['created_at'])) }}</td>
                                        <td>{{ date('Y/m/d',strtotime($report['start_date'])) ." - ". date('Y/m/d',strtotime($report['end_date']))}}</td>
                                        <td class="generated_report_td">
                                            @if($report->export_csv == 1)
                                            <a href="{{route('client.report-export',$report->id)}}?type=csv" target="_blank"  data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Export CSV" ><img src="{{url('/')}}/assets/Images/Sidebar_Icons/pos.png" alt=""></a>
                                                @endif
                                            @if($report->export_pdf == 1)
                                            <a href="{{route('client.report-export',$report->id)}}?type=pdf" target="_blank"  data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Export PDF"><img src="{{url('/')}}/assets/Images/Sidebar_Icons/pos.png" alt=""></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="RequestReportModal" tabindex="-1" aria-labelledby="RequestReportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RequestReportModalLabel">Generate Report</h5>
                </div>
                <form id="requestReportForm" action="{{ route('client.report.generate.create') }}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-floating mt-4">
                            <input type="date" class="form-control" name="date_start" id="date_start" value="">
                            <label for="date" class="form-label">Activity from <span
                                    class="text-danger">*</span></label>
                            <span class="text-danger dateError"></span>
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="date" class="form-control" name="date_end" id="date_end" value="">
                            <label for="date" class="form-label">to <span class="text-danger">*</span></label>
                            <span class="text-danger dateError"></span>
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <input type="text" class="form-control" name="report_title" id="reportTitle"
                                value="{{ old('report_title') }}" placeholder="" />
                            <label for="reportTitle" class="form-label">Customized Report Title <span
                                    class="text-danger">*</span></label>
                            <span class="text-danger reportTitleError"></span>
                        </div>
                        <div class="col-md-6 mt-3">
                            <input type="checkbox" name="pdf_report" class="form-check-input" id="pdf_report"
                                class="me-2">
                            <label class="d-inline-block" for="requestVerify">
                                <small>Include PDF.</small>
                            </label>
                            <span class="text-danger requestVerifyError"></span>
                        </div>
                        <div class="col-md-6 mt-3">
                            <input type="checkbox" name="csv_report" class="form-check-input" id="csv_report"
                                class="me-2">
                            <label class="d-inline-block" for="requestVerify">
                                <small>Include CSV.</small>
                            </label>
                            <span class="text-danger requestVerifyError"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn gc_btn requestreview">Create Report</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e){
            $('[data-bs-toggle="tooltip"]').tooltip();
        })
    </script>
@endsection
