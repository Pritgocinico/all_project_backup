@extends('admin.layouts.app')
@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">View Feedback</h3>
            </div>
            <div class="">
                <a href="{{ route('feedbacks') }}" class="btn btn-primary ms-auto">
                    <i class="sub-menu-arrow ti-angle-left me-2"></i> Back
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="form-row row">
                    <div class="form-group col-md-6">
                        <label for="Customer">Name</label>
                        <input type="text" value="{{$feedback->customer_name}}" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="Email">Email</label>
                        <input type="text" value="{{$feedback->email}}" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="Phone">Phone</label>
                        <input type="text" value="{{$feedback->phone}}" class="form-control" readonly>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="Email">Comment</label>
                        <textarea class="form-control" readonly>{{$feedback->comments}}</textarea>
                    </div>

                    <div class="form-group ">
                        <label for="Phone"><strong>Please rate your overall satisfaction</strong></label>
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5" {{ $feedback->rating == 5 ? 'checked' : '' }} disabled>
                            <label for="star5" title="5 stars"></label>
                            <input type="radio" id="star4" name="rating" value="4" {{ $feedback->rating == 4 ? 'checked' : '' }} disabled>
                            <label for="star4" title="4 stars"></label>
                            <input type="radio" id="star3" name="rating" value="3" {{ $feedback->rating == 3 ? 'checked' : '' }} disabled>
                            <label for="star3" title="3 stars"></label>
                            <input type="radio" id="star2" name="rating" value="2" {{ $feedback->rating == 2 ? 'checked' : '' }} disabled>
                            <label for="star2" title="2 stars"></label>
                            <input type="radio" id="star1" name="rating" value="1" {{ $feedback->rating == 1 ? 'checked' : '' }} disabled>
                            <label for="star1" title="1 star"></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <h5 class="mb-0">Feedback Files</h5>
                        </div>
                        <div class="col-12 ">
                            <div class="mt-3">
                                    @foreach ($feedbackfiles as $file)
                                    <?php $image = URL::asset('public/feedback/' . $file->file_name); ?>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                                class="measurementfiles">
                                        </div>
                                        <a href="{{ $image }}" download>{{ $file->file_name }}
                                            <p class="mb-0 text-danger">
                                                <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small></p>
                                        </a>
                                            <a href="{{ $image }}" class="btn btn-primary" download><i
                                                    class="fa fa-download"></i></a>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
