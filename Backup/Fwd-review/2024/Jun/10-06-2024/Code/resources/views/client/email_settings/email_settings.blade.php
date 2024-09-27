@extends('client.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">

    <div class="card my-3">
        <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
            <div class="pe-4 fs-5">Email Drip Campaign</div>
            <div class="ms-auto">
                <div class="d-flex align-items-center">
                    <a href="{{route('client.add.email')}}" class="btn gc_btn">
                        Add New Email
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive p-3">
            <table class="table rwd-table mb-0 review-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Delay Days</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!blank($emails))
                        @foreach ($emails as $email)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$email->email_name}}</td>
                                <td>
                                    @if ($email->delay_days == 0)
                                        Immediately
                                    @elseif($email->delay_days == 1)
                                        Next Day
                                    @elseif($email->delay_days == 2)
                                        In 2 Days
                                    @elseif($email->delay_days == 3)
                                        In 3 Days
                                    @elseif($email->delay_days == 4)
                                        In 4 Days
                                    @elseif($email->delay_days == 5)
                                        In 5 Days
                                    @elseif($email->delay_days == 6)
                                        In 6 Days
                                    @elseif($email->delay_days == 7)
                                        In 7 Days
                                    @elseif($email->delay_days == 14)
                                        In 14 Days
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('client.edit.email',$email->id)}}" class="btn gc_btn">Edit</a>
                                    <a href="{{route('delete.email',$email->id)}}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">Records Not Found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
