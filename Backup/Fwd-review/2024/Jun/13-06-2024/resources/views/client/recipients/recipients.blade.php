@extends('client.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card mt-md-3 mb-3 p-3 d-flex">
        <div class="ms-auto">
            <button type="button" class="btn gc_btn" data-bs-toggle="modal" data-bs-target="#RequestReviewModal">
                Request Review
            </button>
            <button type="button" class="btn gc_btn" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                Bulk Upload
            </button>
        </div>
    </div>
</div>
<div class="gc_row px-md-4 px-2">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-pills gap-2 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="#pills-inqueue" class="nav-link active" id="pills-inqueue-tab" data-bs-toggle="pill" data-bs-target="#pills-inqueue" type="button" role="tab" aria-controls="pills-inqueue" aria-selected="true">In Queue</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-active" class="nav-link" id="pills-active-tab" data-bs-toggle="pill" data-bs-target="#pills-active" type="button" role="tab" aria-controls="pills-active" aria-selected="false">Active</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-complete" class="nav-link" id="pills-complete-tab" data-bs-toggle="pill" data-bs-target="#pills-complete" type="button" role="tab" aria-controls="pills-complete" aria-selected="false">Complete</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-email" class="nav-link" id="pills-email-tab" data-bs-toggle="pill" data-bs-target="#pills-email" type="button" role="tab" aria-controls="pills-email" aria-selected="false">Email Activity</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#pills-funnel" class="nav-link" id="pills-funnel-tab" data-bs-toggle="pill" data-bs-target="#pills-funnel" type="button" role="tab" aria-controls="pills-funnel" aria-selected="false">Funnel Activity</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-body">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-inqueue" role="tabpanel" aria-labelledby="pills-inqueue-tab">
                    <div class="table-responsive">
                        <table id="example" class="table rwd-table mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email/Phone</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody class="records">
                                @if(!blank($inqueue))
                                    @foreach ($inqueue as $recipient)
                                        <tr>
                                            <td data-header="#">{{$loop->index+1}}</td>
                                            <td data-header="Email/Phone">{{$recipient->email}}</td>
                                            <td data-header="Name">{{$recipient->first_name.' '.$recipient->last_name}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-active" role="tabpanel" aria-labelledby="pills-active-tab">
                    <div class="table-responsive">
                        <table id="example3" class="table rwd-table mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email/Phone</th>
                                    <th>Name</th>
                                    <th>Sent</th>
                                    <th>Last Sent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="records">
                                @if(!blank($active))
                                    @foreach ($active as $recipient)
                                        <tr>
                                            <td data-header="#">{{$loop->index+1}}</td>
                                            <td data-header="Email/Phone">{{$recipient->email}}</td>
                                            <td data-header="Name">{{$recipient->first_name.' '.$recipient->last_name}}</td>
                                            <td data-header="Sent">{{$recipient->sent}}</td>
                                            <td data-header="Last Sent">{{$recipient->last_sent}}</td>
                                            <td data-header="Action">
                                                <a href="#" class="btn gc_btn endCampaign" data-id="{{$recipient->id}}">End Campaign</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab">
                    <div class="table-responsive">
                        <table id="example4" class="table rwd-table mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email/Phone</th>
                                    <th>Name</th>
                                    <th>Last Sent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="records">
                                @if(!blank($complete))
                                    @foreach ($complete as $recipient)
                                        <tr>
                                            <td data-header="#">{{$loop->index+1}}</td>
                                            <td data-header="Email/Phone">{{$recipient->email}}</td>
                                            <td data-header="Name">{{$recipient->first_name.' '.$recipient->last_name}}</td>
                                            <td data-header="Last Sent">{{$recipient->last_sent}}</td>
                                            <td data-header="Action">
                                                <a href="#" class="btn gc_btn reactive_recipient" data-id = "{{$recipient->id}}">Reactive</a>
                                                <a href="" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-email" role="tabpanel" aria-labelledby="pills-email-tab">
                    <div class="table-responsive">
                        <table id="example5" class="table rwd-table mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email/Phone</th>
                                    <th>Name</th>
                                    <th>Activity</th>
                                    <!-- <th>Uploaded From</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="records">
                                @if(!blank($complete))
                                    @foreach ($complete as $recipient)
                                        <?php 
                                            $events = DB::table('recipient_email_events')->where('recipient_id',$recipient->id)->distinct()->pluck('event')->all(); 
                                            $cnt = count($events);
                                        ?>
                                        <tr>
                                            <td data-header="#">{{$loop->index+1}}</td>
                                            <td data-header="Email/Phone">{{$recipient->email}}</td>
                                            <td data-header="Name">{{$recipient->first_name.' '.$recipient->last_name}}</td>
                                            <td data-header="Activity">
                                                <?php $i = 0; ?>
                                                @foreach($events as $event)
                                                    <?php $i++; ?>
                                                    {{$event}}@if($i < $cnt), @endif
                                                @endforeach
                                            </td>
                                            <!-- <td data-header="Uploaded From">i</td> -->
                                            <td data-header="Action">
                                                <a href="" class="btn gc_btn">Reactive</a>
                                                <a href="" class="btn btn-danger">Delete</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-funnel" role="tabpanel" aria-labelledby="pills-funnel-tab">
                    <div class="table-responsive">
                        <table id="example6" class="table rwd-table mb-0" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Email/Phone</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody class="records">
                                @if(!blank($funnel_data))
                                    @foreach($funnel_data as $funnel)
                                        <tr>
                                            <td data-header="#">{{$loop->index+1}}</td>
                                            <td data-header="Email/Phone">{{$funnel->email}}</td>
                                            <td data-header="Name">{{$funnel->name}}</td>
                                            <td data-header="Time">{{$funnel->created_at}}</td>
                                            <td data-header="Activity">{{$funnel->action_taken}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RequestReviewModal" tabindex="-1" aria-labelledby="RequestReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="RequestReviewModalLabel">Review Request</h5>
        </div>
            <div class="modal-body">
                <p>Add Individual recipients one-by-one:</p>
                <div class="row">
                    <div class="col-md-12 form-floating mt-4">
                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" placeholder="" />
                        <label for="email" class="form-label">Email Address (required)</label>
                        <span class="text-danger emailError"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="text" class="form-control" name="fisrt_name" id="FirstName" value="{{old('fisrt_name')}}" placeholder="" />
                        <label for="FisrtName" class="form-label">First Name <span class="text-danger">*</span></label>
                        <span class="text-danger firstNameError"></span>
                    </div>
                    <div class="col-md-6 form-floating mt-4">
                        <input type="text" class="form-control" name="last_name" id="LastName" value="{{old('last_name')}}" placeholder="" />
                        <label for="LastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <span class="text-danger lastNameError"></span>
                    </div>
                    <div class="col-md-12 mt-3">
                        <input type="checkbox" name="verify" class="form-check-input" id="requestVerify" checked class="me-2">
                        <label class="d-inline-block" for="requestVerify">
                            <small>I certify that the recipient has opted in to receive these communications.</small>
                        </label>
                        <span class="text-danger requestVerifyError"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="javascript:void(0);" class="btn gc_btn requestreview">Request Review</a>
            </div>
      </div>
    </div>
</div>
<div class="modal fade" id="bulkUploadModal" tabindex="-1" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Recipients via CSV or XLSX</h5>
        </div>
        <form action="{{route('import.request')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div>
                    <label for="File">Choose a file to Upload ( <a href="{{url('/')}}/assets/invite_list_sample.csv" target="_blank" class="text-primary" download> Download Example</a> )</label>
                    <input type="file" name="bulk_upload" class="form-control mt-2" id="File" required>
                </div>
                <div class="mt-3">
                    <input type="checkbox" name="verify" class="form-check-input" id="verify" checked class="me-2" required>
                    <label class="d-inline" for="verify">
                        I certify that all recipients have opted in to receive these communications. I understand and accept the email service provider's terms of service.
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn gc_btn">Bulk Upload</button>
            </div>
        </form>
      </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).on('click','.requestreview',function(){
            $('.firstNameError').html('');
            $('.lastNameError').html('');
            $('.emailError').html('');
            $('.requestVerifyError').html('');
            var first_name = $('#FirstName').val();
            var last_name = $('#LastName').val();
            var email = $('#email').val();
            var verify = $('#requestVerify').val();
            console.log(verify);
            if(first_name == ''){
                $('.firstNameError').html('Please enter first name');
            }
            if(last_name == ''){
                $('.lastNameError').html('Please enter last name');
            }
            if(email == ''){
                $('.emailError').html('Please enter email');
            }
            if(!$('#requestVerify').is(':checked')){
                $('.requestVerifyError').html('Please check');
            }
            // var formData = new FormData($('#requestForm')[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : "{{route('request.review')}}",
                data : {"_token": "{{ csrf_token() }}",'email': email,'first_name': first_name,'last_name': last_name},
                type : 'POST',
                success : function(data) {
                    location.reload();
                }
            });
        })

        $(document).on('click','.reactive_recipient',function(){
            var recipient_id = $(this).data('id');
            $.ajax({
                url : "{{route('reactive.recipient')}}",
                data : {id : recipient_id},
                type : 'GET',
                success : function(data) {
                    location.reload();
                }
            });
        })

        $(document).on('click','.endCampaign',function(){
            var recipient_id = $(this).data('id');
            $.ajax({
                url : "{{route('end.campaign')}}",
                data : {id : recipient_id},
                type : 'GET',
                success : function(data) {
                    location.reload();
                }
            });
        })
</script>
@endsection
