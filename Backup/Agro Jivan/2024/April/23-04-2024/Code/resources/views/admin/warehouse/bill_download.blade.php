@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" placeholder="Search"
                                            onkeyup="batchAjaxList(1)" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Search Driver:</label>
                                                        <select name="diver_id" id="diver_id" class="form-control">
                                                            <option value="">Select Driver</option>
                                                            @foreach ($driverList as $driver)
                                                                <option value="{{ $driver->id }}">{{ $driver->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                        <input type="text" name="batch_date" id="batch_date"
                                                            class="form-control form-select-solid fw-bold search_batch_date"
                                                            max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="billDownloadAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="bill_table_ajax"></div>
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
@endsection
@section('page')
    <script>
        $(function() {
            $('.search_batch_date').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
            }, function(start, end, label) {
                $('.search_batch_date').val(start.format('Y-0M-0D') + '/' + end.format('Y-0M-0D'));
            });
        });
        $(document).ready(function(e) {
            billDownloadAjaxList(1)
        })
        function resetForm(){
            $('#search_data').val('')
            $('#batch_date').val('')
            $('#diver_id').val('')
            billDownloadAjaxList(1)   
        }

        function billDownloadAjaxList(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('bill-download-ajax') }}",
                data: {
                    page: page,
                    search: $('#search_data').val(),
                    date: $('#batch_date').val(),
                    driverId: $('#diver_id').val(),
                },
                success: function(res) {
                    $('#bill_table_ajax').html('');
                    $('#bill_table_ajax').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            billDownloadAjaxList(page);
        });
    </script>
@endsection
