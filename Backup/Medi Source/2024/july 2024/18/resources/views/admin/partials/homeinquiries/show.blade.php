<!-- resources/views/frontend/homeinquiries/show.blade.php -->

@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Home Inquiry Details</h3>
                            <a href="{{ route('homeinquiries.index') }}" class="btn btn-secondary">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name:</th>
                                <td>{{ $inquiry->name }}</td>
                            </tr>
                            <tr>
                                <th>Contact:</th>
                                <td>{{ $inquiry->contact }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $inquiry->email }}</td>
                            </tr>
                            <tr>
                                <th>State:</th>
                                <td>{{ $inquiry->state }}</td>
                            </tr>
                            <tr>
                                <th>Message:</th>
                                <td>{{ $inquiry->message }}</td>
                            </tr>
                            <tr>
                                <th>Consent:</th>
                                <td>{{ $inquiry->consent ? 'Yes' : 'No' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
