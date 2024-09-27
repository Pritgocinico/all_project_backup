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
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="feedbackAjax(1)"
                                            placeholder="Search Feedback" />
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
@endsection
@section('page')
    <script src="{{ asset('public/assets/plugins/custom/rate/jquery.rateyo.min.js') }}"></script>
    <script>
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
    </script>
@endsection
