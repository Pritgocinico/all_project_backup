<!-- resources/views/frontend/inquiries/show.blade.php -->

@extends('admin.layouts.app')  <!-- Adjust this to your actual layout file -->

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-header">
    <div class="container ">
        <div class="d-flex top-head ">
        <h3>Contact Inquiry Details</h3>
        <a href="{{ route('inquiries.index') }}" class="btn go-back-btn btn-secondary">Go Back</a>
        </div>
        <table class="table table-bordered">
          
            <tr>
                <td>User Type</td>
                <td>{{ $inquiry->user_type }}</td>
            </tr>
            <tr>
                <td >Clinical Difference</td>
                <td>{{ $inquiry->clinical_difference }}</td>
            </tr>
            <tr>
                <td>First Name</td>
                <td>{{ $inquiry->first_name }}</td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td>{{ $inquiry->last_name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $inquiry->email }}</td>
            </tr>
            <tr>
                <td>Phone</td>
                <td>{{ $inquiry->phone }}</td>
            </tr>
            <tr>
                <td>Clinic Name</td>
                <td>{{ $inquiry->clinic_name }}</td>
            </tr>
            <tr>
                <td>Website</td>
                <td>{{ $inquiry->website }}</td>
            </tr>
            <tr>
                <td>Number Of Physicians</td>
                <td>{{ $inquiry->number_of_physicians }}</td>
            </tr>
            <tr>
                <td>Number of Locations</td>
                <td>{{ $inquiry->number_of_locations }}</td>
            </tr>
            <tr>
                <td>License Number</td>
                <td>{{ $inquiry->license_number }}</td>
            </tr>
            <tr>
                <td>DEA Number</td>
                <td>{{ $inquiry->dea_number }}</td>
            </tr>
            <tr>
                <td>Products/Services Interested In</td>
                <td>
                    <ul>
                        @foreach(json_decode($inquiry->products_services_interested) as $interest)
                            <li style="list-style:none;">{{ $interest }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            <tr>
                <td>Description</td>
                <td>{{ $inquiry->description }}</td>
            </tr>
        </table>
    </div>
@endsection
