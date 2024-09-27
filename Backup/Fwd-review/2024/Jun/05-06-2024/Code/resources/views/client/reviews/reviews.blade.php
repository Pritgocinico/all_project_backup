@extends('client.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-3">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                </ol>
            </nav>
        </div>
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">My Reviews</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if (!blank($message))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>{{ $message }}
                    </div>
                @endif
                <ul class="nav nav-pills gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#pills-reviews" class="nav-link active" id="pills-reviews-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-reviews" type="button" role="tab" aria-controls="pills-reviews"
                            aria-selected="true">All Reviews</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-feedback" class="nav-link" id="pills-feedback-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-feedback" type="button" role="tab" aria-controls="pills-feedback"
                            aria-selected="false">Customer Feedback</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-reviews" role="tabpanel" aria-labelledby="pills-reviews-tab">
                <div class="card">
                    <div class="table-responsive p-3">
                        <table id="example" class="table rwd-table mb-0 review-table">
                            <thead>
                                <tr>
                                    <th>Site</th>
                                    <th>Date</th>
                                    <th>Attribution</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($data))
                                    @foreach ($data as $review)
                                        {{-- @dd($data) --}}
                                        <tr>
                                            <td class="w-5" data-header="Site"><img
                                                    src="{{ url('/') }}/assets/Images/google.png" width="30px"
                                                    alt="Google"></td>
                                            <td class="w-10" data-header="Date" class="pt-2">
                                                {{ date('Y-m-d', $review['time']) }}</td>
                                            <td class="w-10" data-header="Attribution">
                                                {{-- <div class="text-center"> --}}
                                                <span class="me-2 text-white">
                                                    <img src="{{ $review['profile_photo_url'] }}" width="30px">
                                                </span>
                                                <span>{{ $review['author_name'] }}</span>
                                                {{-- </div> --}}
                                            </td>
                                            <td class="w-15" data-header="Rating">
                                                <div class="star-rating">
                                                    <label for="star-5" title="5 stars">
                                                        <i class="@if ($review['rating'] >= 5) active @endif fa fa-star"
                                                            aria-hidden="true"></i>
                                                    </label>
                                                    <label for="star-4" title="4 stars">
                                                        <i class="@if ($review['rating'] >= 4) active @endif fa fa-star"
                                                            aria-hidden="true"></i>
                                                    </label>
                                                    <label for="star-3" title="3 stars">
                                                        <i class="@if ($review['rating'] >= 3) active @endif fa fa-star"
                                                            aria-hidden="true"></i>
                                                    </label>
                                                    <label for="star-2" title="2 stars">
                                                        <i class="@if ($review['rating'] >= 2) active @endif fa fa-star"
                                                            aria-hidden="true"></i>
                                                    </label>
                                                    <label for="star-1" title="1 star">
                                                        <i class="@if ($review['rating'] >= 1) active @endif fa fa-star"
                                                            aria-hidden="true"></i>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="w-40" data-header="Review">{{ $review['text'] }}</td>
                                            <td class="w-20" data-header="Status">
                                                <a href="javascript:void(0)" class="btn gc_btn" onclick="copyText()">Share</a>
                                                <a href="http://search.google.com/local/reviews?placeid={{$business->place_id}}"
                                                    target="_blank" class="btn gc_btn">Respond</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">Reviews Not Found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-feedback" role="tabpanel" aria-labelledby="pills-feedback-tab">
                <div class="card">
                    <div class="text-end px-3">
                        <a id="export" class="btn gc_btn" target="_blank">Export Excel</a>
                    </div>
                    <div class="table-responsive p-3">
                        <table id="example3" class="table rwd-table mb-0 review-table">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Date</th>
                                    <th width="30%">Customer Details</th>
                                    <th width="40%">Message</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($feedbacks))
                                    @foreach ($feedbacks as $feedback)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $feedback->created_at }}</td>
                                            <td>
                                                <div>
                                                    <p><strong>Name: </strong>{{ $feedback->name }}</p>
                                                    <p><strong>Action Taken: </strong>{{ $feedback->action_taken }}</p>
                                                    <p><strong>Phone: </strong>{{ $feedback->phone }}</p>
                                                    <p><strong>Email: </strong>{{ $feedback->email }}</p>
                                                </div>
                                            </td>
                                            <td>{{ $feedback->message }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="javascript:void(0);" class="btn gc_btn deleteFeedback"
                                                        data-id="{{ $feedback->id }}">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Records Not Found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('click', '.deleteFeedback', function() {
            var id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.feedback', '') }}" + "/" + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "customer Feedback has been deleted.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });
        $('#export').click(function() {
            var search = $('#example3_filter .input-sm').val();
            window.open(
                "{{ route('business.review.export') }}?search=" + search,
                '_blank'
            );
        })

        function copyText() {

/* Copy text into clipboard */
navigator.clipboard.writeText
    ("http://search.google.com/local/reviews?placeid={{$business->place_id}}");
}
    </script>
@endsection
