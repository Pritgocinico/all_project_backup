@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    @php $text = "Employee" @endphp
                    @if($user->role == 3)
                    @php $text = "Doctor" @endphp
                    @endif
                    <h2 class="mb-0">View {{$text}} Details</h2>
                    @if (PermissionHelper::checkUserPermission('Doctor Status Update'))
                    <div class="ms-auto">
                        @if($user->status == 'inactive')

                        <form action="{{ route('admin.user.updateStatus', ['id' => $user->id]) }}" method="post" id="updateStatusForm">
                            @csrf                                
                            <button class="btn btn-primary" type="submit" name="status" value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                                {{ $user->status === 'active' ? 'Inactive' : 'Active Now' }}
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.user.updateStatus', ['id' => $user->id]) }}" method="post" id="updateStatusForm">
                            @csrf                                
                            <button class="btn btn-primary" type="submit" name="status" value="active">
                                Resend Activation & Password
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif

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
                        <th>{{$text}} Name</th>
                        <td>{{$user->first_name.' '.$user->last_name}}</td>
                    </tr>
                    
                    <tr>
                        <th>{{$text}} Created Date</th>
                        <td>{{date('d/m/Y H:i:s',strtotime($user->created_at))}}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{$user->phone}}</td>
                    </tr>
                    @if($user->role == 2)
                    <tr>
                        <th>Role Name</th>
                        <td>
                            @if(isset($user->role_name))
                            {{$user->role_name}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    @endif
                    @if($user->role == 3)
                    <tr>
                        <th> Organization Name</th>
                        <td>
                            @if(isset($user->organization_name))
                            {{$user->organization_name}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>
                            @if(isset($user->practice_address_street))
                            {{$user->practice_address_street}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>
                            @if(isset($user->city))
                            {{$user->city}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>
                            @if(isset($user->state))
                            {{$user->state}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>Zip Code</th>
                        <td>
                            @if(isset($user->zip_code))
                            {{$user->zip_code}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>NPI</th>
                        <td>
                            @if(isset($user->npi))
                            {{$user->npi}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>Business License Number</th>
                        <td>
                            @if(isset($user->business_license_number))
                            {{$user->business_license_number}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>Prescriber State License Number</th>
                        <td>
                            @if(isset($user->prescriber_state_license_number))
                            {{$user->prescriber_state_license_number}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>DEA Number</th>
                        <td>
                            @if(isset($user->dea_number))
                            {{$user->dea_number}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                   
                    <tr>
                        <th>EIN Number</th>
                        <td>
                            @if(isset($user->ein_number))
                            {{$user->ein_number}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th>Speciality</th>
                        <td>
                            @if(isset($user->speciality))
                            {{$user->speciality}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    <tr>
                        <th> VAT Registration No</th>
                        <td>
                            @if(isset($user->vat_reg_no))
                            {{$user->vat_reg_no}}
                            @else
                            -
                            @endif  
                        </td>
                    </tr>
                    
                    <tr>
                        <th>Account Manager Name</th>
                        <td>
                        @php
                            $manager = \App\Models\User::find($user->account_manager_name);
                            $manage_name = $manager ? $manager->name : '-';
                        @endphp
                        {{ $manage_name }}     
                        </td>
                    </tr>
                    @endif                      
                </tbody>
                </table>

                </table>
            </div>
        </div>
        @if($user->role !== 2)
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table" id="userTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Document</th>
                                    <th>View</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->userDocument as $document)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a href="{{url('/')}}/public/storage/{{$document->document}}" target="_blank"><i class="fa-solid fa-file text-primary" style="font-size: 50px;"></i></a></td>
                                    <td><a href="{{url('/')}}/public/storage/{{$document->document}}" class="btn btn-primary" target="_blank" >View Document</a></td>
                                    <td><a href="{{ route('admin.delete.document' , $document->id) }}" class="btn btn-danger" onclick="showLoader(this)"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="container">
                                <form action="{{ route('admin.upload.document') }}" method="post" enctype="multipart/form-data" id="documentForm">
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
                                <div id="loader" style="display: none;">Loading...</div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(PermissionHelper::checkUserPermission('Doctor Card Information'))
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Card Detail</h3>
                        <table class="table" id="userTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Card Name</th>
                                    <th>Card Number</th>
                                    <th>Expire Month</th>
                                    <th>Expire Year</th>
                                    <th>CVV Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->cardDetail as $card)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{$card->card_name}}</td>
                                    <td>{{$card->card_number}}</td>
                                    <td>{{$card->expire_month}}</td>
                                    <td>{{$card->expire_year}}</td>
                                    <td>{{$card->cvv_number}}</td>
                                    <td>
                                        @if($user->card_id == $card->id)
                                            <button class="btn btn-secondary">Default</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
<script>

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('documentForm').addEventListener('submit', function() {
        document.getElementById('validateForm').innerHTML = 'Loading... &nbsp; <i class="fa fa-spinner fa-spin"></i>';
    });
});
</script>
@endsection