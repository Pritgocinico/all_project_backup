@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                Pending Order
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="app-content  flex-column-fluid ">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                            <div class="card">
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" placeholder="Search Order" />
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="pending_ajax_list">
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
@endsection
@section('page')
    <script>
        var ajaxList = "{{ route('confirm-all-order-ajax') }}";
        var confirmURL = "{{ route('manual-orders-confirm') }}";
        var deleteURL = "{{route('manual-orders-cancel')}}";
        var token = "{{ csrf_token() }}"
    </script>
    <script>
        $(document).ready(function(e) {
            pendingOrderAjax(1)
        })

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            pendingOrderAjax(page);
        });

        function pendingOrderAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': ajaxList,
                data: {
                    page: page,
                    search: search,
                },
                success: function(res) {
                    $('#pending_ajax_list').html('');
                    $('#pending_ajax_list').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }

        function confirmOrder(id) {
            var url = confirmURL
            new swal({
                title: 'Are you sure confirm this order?',
                showCancelButton: true,
                confirmButtonColor: '#17c653',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Confirm it!',
                cancelButtonText: 'Close',
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            _token: token,
                            id: id,
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            pendingOrderAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }

        function cancelOrder(id) {
            var url = deleteURL
            new swal({
                title: 'Are you sure cancel this order?',
                text: "Enter Reason for cancellation",
                showCancelButton: true,
                input: 'text',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Cancel it!',
                cancelButtonText: 'Close',
                customClass: {
                    validationMessage: 'my-validation-message',
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Reason for cancellation is required')
                    }
                },
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            _token: token,
                            id: id,
                            reason: isConfirm.value
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            pendingOrderAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }
    </script>
@endsection
