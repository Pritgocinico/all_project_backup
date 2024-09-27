@extends('admin.layouts.app')

@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Feedbacks</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Feedback Date</th>
                            <th>Feedback Stars</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($feedbacks))
                            @foreach ($feedbacks as $feedback)
                                <tr>
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                       {{$feedback->customer_name}}
                                    </td>
                                    <td>
                                        {{$feedback->email}}
                                    </td>
                                    <td>
                                        {{$feedback->phone}}
                                    </td>
                                    <td>
                                        {{ date('d/m/Y - H:i:s', strtotime($feedback->created_at)) }}
                                    </td>
                                    <td>
                                        <div class="rating">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $feedback->rating)
                                                    <a class="star_font_size">
                                                        <i class="icon-star warning-color" data-feather="star"></i>
                                                    </a>
                                                @else
                                                    <a class="star_font_size">
                                                        <i data-feather="star"></i>
                                                    </a>
                                                @endif
                                            @endfor
                                        </div>
                                         ({{$feedback->rating}})
                                    </td>
                                    <td>
                                        <a href="{{ route('viewFeedback', $feedback->id) }}" class="viewUser"><i
                                                data-feather="eye"></i></a>
                                        <a href="javascript:void(0);" data-id="{{ $feedback->id }}"
                                            class="ms-2 delete-btn feedback_delete_btn"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif                       
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Feedback Date</th>
                            <th>Feedback Stars</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
<style>
    .warning-color {
    color: #ffc107; /* Yellow color for warning */
}

</style>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.feedback_delete_btn', function() {
                var feedback_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this Feedback!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('deleteFeedback', '') }}" + "/" + feedback_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Feedback has been deleted.",
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
        });
    </script>
@endsection
