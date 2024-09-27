@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Help Inquiry Details</h3>
                            <a href="{{ route('helpinquiries.index') }}" class="btn btn-secondary">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <div class="inquiry-details-box py-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name:</th>
                                    <td>{{ $inquiry->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $inquiry->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $inquiry->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Message:</th>
                                    <td>{{ $inquiry->message }}</td>
                                </tr>
                                <tr>
                                    <th>Team Member Contact:</th>
                                    <td>{{ $inquiry->team_member_contact ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email List:</th>
                                    <td>{{ $inquiry->sign_up_for_email_list ? 'Yes' : 'No' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
