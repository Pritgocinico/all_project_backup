@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0">View User Details</h2>
                    <div class="ms-auto">
                        @if($user->status == 'inactive')

                        <form action="{{ route('admin.user.updateStatus', ['id' => $user->id]) }}" method="post" id="updateStatusForm">
                            @csrf

                            <div class="btn-group">
                                <button type="button" class="btn btn-{{ $user->status === 'active' ? 'success' : 'danger' }} btn-sm">
                                    {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
                                </button>
                                <button type="button" class="btn btn-{{ $user->status === 'active' ? 'success' : 'danger' }} btn-sm dropdown-toggle dropdown-toggle-split" id="statusDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                    <li>
                                        <button class="dropdown-item btn-sm" type="submit" name="status" value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                                            {{ $user->status === 'active' ? 'Inactive' : 'Active' }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </form>
                        @endif
                    </div>

                    <a href="#" onclick="history.back()" class="btn btn-secondary ms-auto">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <table class="table">
                    <tr>
                        <th>Organization Name</th>
                        <td>{{$user->organization_name}}</td>
                    </tr>
                    <tr>
                        <th>User Name</th>
                        <td>{{$user->first_name.' '.$user->last_name}}</td>
                    </tr>
                    <tr>
                        <th>User Created Date</th>
                        <td>{{date('d/m/Y',strtotime($user->created_at))}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{$user->phone}}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{$user->practice_address_street}}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{$user->state}}</td>
                    </tr>

                    </tbody>
                </table>

                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table" id="userTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Document</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->userDocument as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a href="{{url('/')}}/public/storage/{{$user->document}}" target="_blank"><i class="fa-solid fa-file text-primary" style="font-size: 50px;"></i></a></td>
                                    <td><a href="{{url('/')}}/public/storage/{{$user->document}}" class="btn btn-primary" target="_blank">View Document</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="container">
                                    <form action="{{ route('admin.upload.document') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{$id}}">
                                        <div class="form-group mb-3">
                                            <label for="first_name">Upload Document:</label>
                                            <input type="file" class="form-control" id="upload_document[]" name="upload_document[]" required multiple>
                                            @if ($errors->has('upload_document'))
                                            <div class="error">{{ $errors->first('upload_document') }}</div>
                                            @endif
                                            <span class="text-danger" id="upload_document_error"></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="validateForm">Upload Document</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection