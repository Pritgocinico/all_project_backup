@extends('admin.layouts.app')

@section('style')
<link rel="stylesheet" type="text/css" href="{{url('/')}}/assets/css/vendors/dropzone.css">
<style>
    .dz-thumbnail {
        max-width: 150px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.edit.claim.data')}}" method="post" class="row g-3" id="formDropzone" enctype="multipart/form-data">
                @csrf
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="claim_date" id="Date" value="{{$claim->claim_date}}" placeholder="" autofocus />
                    <label for="Date" class="form-label">Claim Date <span class="text-danger">*</span></label>
                    @if ($errors->has('claim_date'))
                        <span class="text-danger">{{ $errors->first('claim_date') }}</span>
                    @endif
                    <span class="text-danger claim_date"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="claim_no" id="ClaimNo" value="{{$claim->claim_no}}" placeholder="" autofocus />
                    <label for="ClaimNo" class="form-label">Claim No <span class="text-danger">*</span></label>
                    @if ($errors->has('claim_no'))
                        <span class="text-danger">{{ $errors->first('claim_no') }}</span>
                    @endif
                    <span class="text-danger claim_no"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="contact_person" id="ContactPerson" value="{{$claim->contact_person}}" placeholder="" autofocus />
                    <label for="ContactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
                    @if ($errors->has('contact_person'))
                        <span class="text-danger">{{ $errors->first('contact_person') }}</span>
                    @endif
                    <span class="text-danger contact_person"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="contact_person_no" id="ContactPersonNo" value="{{$claim->contact_person_no}}" placeholder="" autofocus />
                    <label for="ContactPersonNo" class="form-label">Contact Person No<span class="text-danger">*</span></label>
                    @if ($errors->has('contact_person_no'))
                        <span class="text-danger">{{ $errors->first('contact_person_no') }}</span>
                    @endif
                    <span class="text-danger contact_person_no"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="surveyar_name" id="SurveyarName" value="{{$claim->surveyar_name}}" placeholder="" autofocus />
                    <label for="SurveyarName" class="form-label">Surveyar Name </label>
                    @if ($errors->has('surveyar_name'))
                        <span class="text-danger">{{ $errors->first('surveyar_name') }}</span>
                    @endif
                    <span class="text-danger surveyar_name"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="surveyar_no" id="SurveyarNo" value="{{$claim->surveyar_no}}" placeholder="" autofocus />
                    <label for="SurveyarNo" class="form-label">Surveyar No </label>
                    @if ($errors->has('surveyar_no'))
                        <span class="text-danger">{{ $errors->first('surveyar_no') }}</span>
                    @endif
                    <span class="text-danger surveyar_no"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="email" class="form-control" name="surveyar_email" id="SurveyarEmail" value="{{$claim->surveyar_email}}" placeholder="" autofocus />
                    <label for="SurveyarEmail" class="form-label">SurveyarEmail</label>
                    @if ($errors->has('surveyar_email'))
                        <span class="text-danger">{{ $errors->first('surveyar_email') }}</span>
                    @endif
                    <span class="text-danger surveyar_email"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="repairing_location" id="RepairingLocation" value="{{$claim->repairing_location}}" placeholder="" />
                    <label for="RepairingLocation" class="form-label">RePairing Location</label>
                    @if ($errors->has('repairing_location'))
                        <span class="text-danger">{{ $errors->first('repairing_location') }}</span>
                    @endif
                    <span class="text-danger repairing_location"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <select name="claim_type" id="ClaimType" class="form-control">
                        <option value="0" >Select claim type...</option>
                        <option value="1" @if($claim->claim_type == 1) selected @endif>OWN_DAMAGE</option>
                        <option value="2" @if($claim->claim_type == 2) selected @endif>THIRD_PARTY</option>
                    </select>
                    <label for="ClaimType" class="form-label">Claim Type <span class="text-danger">*</span></label>
                    @if ($errors->has('claim_type'))
                        <span class="text-danger">{{ $errors->first('claim_type') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <select name="claim_status" id="ClaimStatus" class="form-control">
                        <option value="1" @if($claim->claim_status == 1) selected @endif>Open</option>
                        <option value="2" @if($claim->claim_status == 2) selected @endif>Close</option>
                        <option value="3" @if($claim->claim_status == 3) selected @endif>Repuidated</option>
                    </select>
                    <label for="ClaimStatus" class="form-label">Claim Status <span class="text-danger">*</span></label>
                    @if ($errors->has('claim_status'))
                        <span class="text-danger">{{ $errors->first('claim_status') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="status_text" id="StatusText" value="{{$claim->status_text}}" placeholder="" />
                    <label for="StatusText" class="form-label">Status Text</label>
                    @if ($errors->has('status_text'))
                        <span class="text-danger">{{ $errors->first('status_text') }}</span>
                    @endif
                    <span class="text-danger status_text"></span>
                </div>
                <div class="col-md-6 form-floating mt-4 status" @if($claim->claim_status == 1) style="display: none;" @endif>
                    <input type="date" class="form-control" name="status_date" id="StatusDate" value="{{date('Y-m-d',strtotime($claim->status_date))}}" placeholder="" />
                    <label for="StatusDate" class="form-label">Status Date</label>
                    @if ($errors->has('status_date'))
                        <span class="text-danger">{{ $errors->first('status_date') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <select name="payment_type" id="PaymentType" class="form-control">
                        <option value="1" @if($claim->payment_type == 1) selected @endif>Cash</option>
                        <option value="2" @if($claim->payment_type == 2) selected @endif>Cheque</option>
                        <option value="3" @if($claim->payment_type == 3) selected @endif>Online</option>
                    </select>
                    <label for="PaymentType" class="form-label">Payment Type <span class="text-danger">*</span></label>
                    @if ($errors->has('payment_type'))
                        <span class="text-danger">{{ $errors->first('payment_type') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4 cheque" @if($claim->payment_type != 2) style="display:none;" @endif>
                    <input type="text" class="form-control" name="cheque_no" id="chequeNo" value="{{$claim->cheque_no}}" placeholder="" />
                    <label for="chequeNo" class="form-label">Cheque No </label>
                    @if ($errors->has('cheque_no'))
                        <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                    @endif
                    <span class="text-danger cheque_no"></span>
                </div>
                <div class="col-md-6 form-floating mt-4 cheque" @if($claim->payment_type != 2) style="display:none;" @endif>
                    <input type="date" class="form-control" name="cheque_date" id="chequeDate" value="{{$claim->cheque_date}}" placeholder="" />
                    <label for="chequeDate" class="form-label">Cheque Date <span class="text-danger">*</span></label>
                    @if ($errors->has('cheque_date'))
                        <span class="text-danger">{{ $errors->first('cheque_date') }}</span>
                    @endif
                    <span class="text-danger cheque_date"></span>
                </div>
                <div class="col-md-6 form-floating mt-4 cheque" @if($claim->payment_type != 2) style="display:none;" @endif>
                    <input type="text" class="form-control" min="0" name="bank_name" id="BankName" value="{{$claim->bank_name}}" placeholder="" />
                    <label for="BankName" class="form-label">Bank Name <span class="text-danger">*</span></label>
                    @if ($errors->has('bank_name'))
                        <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                    @endif
                    <span class="text-danger bank_name"></span>
                </div>
                <div class="col-md-6 form-floating mt-4 online" @if($claim->payment_type != 3) style="display: none;" @endif>
                    <input type="text" class="form-control" name="transaction_no" id="TransactionNo" value="{{$claim->transaction_no}}" placeholder="" />
                    <label for="TransactionNo" class="form-label">Transaction No <span class="text-danger">*</span></label>
                    @if ($errors->has('transaction_no'))
                        <span class="text-danger">{{ $errors->first('transaction_no') }}</span>
                    @endif
                    <span class="text-danger transaction_no"></span>
                </div>
                {{-- <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="remarks" id="Remarks" value="{{$claim->remarks}}" placeholder="" />
                    <label for="Remarks" class="form-label">Remarks </label>
                    @if ($errors->has('remarks'))
                        <span class="text-danger">{{ $errors->first('remarks') }}</span>
                    @endif
                </div> --}}
                <div class="form-group mb-4 mt-4">
                    <label class="form-label opacity-75 fw-medium" for="formImage">Claim Attachments</label>
                    <div class="dropzone-drag-area dropzone dropzone-secondary" id="previews">
                        <div class="dz-message needsclick text-muted opacity-50" data-dz-message>
                            <i class="icon-cloud-up"></i><br>
                            <span>Drag file here to upload</span>
                        </div>
                        <div class="d-flex" id="previews1"></div>
                        <div class="d-none" id="dzPreviewContainer">
                            <div class="dz-file-preview">
                                <div class="dz-photo">
                                    <img class="dz-thumbnail" data-dz-thumbnail>
                                </div>
                                <div class="dz-name"></div>
                                <button class="dz-delete border-0 p-0" type="button" data-dz-remove>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="times"><path fill="#FFFFFF" d="M13.41,12l4.3-4.29a1,1,0,1,0-1.42-1.42L12,10.59,7.71,6.29A1,1,0,0,0,6.29,7.71L10.59,12l-4.3,4.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L12,13.41l4.29,4.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback fw-bold">Please upload an image.</div>
                </div>
                <div class="col-12">
                    <input type="hidden" name="id" value="{{$claim->id}}">
                    <input type="hidden" name="policy_id" value="{{$claim->policy_id}}">
                    <button type="button" id="formSubmit" class="btn btn-primary mt-3">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js"></script>
    <script>
        $(document).on('change','#PaymentType',function(){
            var type = $(this).val();
            if(type == 2){
                $('.cheque').css('display','inline-block');
                $('.online').css('display','none');
            }else if(type == 3){
                $('.cheque').css('display','none');
                $('.online').css('display','inline-block');
            }else{
                $('.cheque').css('display','none');
                $('.online').css('display','none');
            }
        });
        $(document).on('change','#ClaimStatus',function(){
            var status = $(this).val();
            if(status == 2){
                $('.status').css('display','inline-block');
            }else if(status == 3){
                $('.status').css('display','inline-block');
            }else{
                $('.status').css('display','none');
            }
        });
    </script>
    <script>
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone('#formDropzone',{
            previewTemplate: $('#dzPreviewContainer').html(),
            url: "{{route('admin.edit.claim.data')}}",
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 10,
            maxFiles: 10,
            acceptedFiles: '.jpeg, .jpg, .png, .gif, .pdf, .docx',
            thumbnailWidth: 200,
            thumbnailHeight: 200,
            previewsContainer: "#previews1",
            timeout: 0,
            init: function()
            {
                // myDropzone1 = this;
                // when file is dragged in
                this.on('addedfile', function(file) {
                    var ext = file.name.split('.').pop();
                    $(file.previewElement).find(".dz-name").html(file.name);
                    if (ext == "pdf") {
                        $(file.previewElement).find(".dz-photo img").attr("src", "{{url('/')}}/assets/Images/pdf.png");
                    } else if (ext.indexOf("doc") != -1) {
                        $(file.previewElement).find(".dz-photo img").attr("src", "{{url('/')}}/assets/Images/docs.png");
                    } else if (ext.indexOf("xls") != -1) {
                        $(file.previewElement).find(".dz-photo img").attr("src", "{{url('/')}}/assets/Images/docs.png");
                    }
                    $('.dropzone-drag-area').removeClass('is-invalid').next('.invalid-feedback').hide();
                });
                $.ajax({
                    type: 'get',
                    url: "{{route('admin.get_claim_attachment',$claim->id)}}",
                    success: function(mocks){
                        $.each(mocks, function(key,value) {

                            let mockFile = { name: value.name, size: 1024, accepted: true, status: myDropzone.QUEUED};

                            myDropzone.displayExistingFile(mockFile, value.url);
                        });
                    }
                });
            },
            success: function(file, response)
            {
                // hide form and show success message
                // $('#formDropzone').fadeOut(600);
                // if(response == ''){
                    top.location.href="{{ route('admin.claims',$claim->policy_id) }}";
                // }
                // setTimeout(function() {
                //     $('#successMessage').removeClass('d-none');
                // }, 600);
            }
        });

        $('#formSubmit').on('click', function(event) {
            event.preventDefault();
            var $this = $(this);

            // show submit button spinner
            $this.children('.spinner-border').removeClass('d-none');

            // validate form & submit if valid
            if ($('#formDropzone')[0].checkValidity() === false) {
                event.stopPropagation();
                // show error messages & hide button spinner
                $('#formDropzone').addClass('was-validated');
                $this.children('.spinner-border').addClass('d-none');

                // if dropzone is empty show error message
                if (!myDropzone.getQueuedFiles().length > 0) {
                    $('.dropzone-drag-area').addClass('is-invalid').next('.invalid-feedback').show();
                }
            } else {
                if (myDropzone.files.length > 0) {
                    // var dr = document.querySelector("#formDropzone").dropzone;
                    // console.log(dr);
                    // myDropzone.processQueue();
                // if everything is ok, submit the form
                    var claim_date          = $('#Date').val();
                    var claim_no            = $('#ClaimNo').val();
                    var contact_person      = $('#ContactPerson').val();
                    var contact_person_no   = $('#ContactPersonNo').val();
                    var cheque_date         = $('#chequeDate').val();
                    var bank_name           = $('#BankName').val();
                    var transaction_no      = $('#TransactionNo').val();

                    var i = 0;
                    if(claim_date == 0){
                        i++;
                        $('.claim_date').html('Claim date field is required.');
                    }
                    if(claim_no == ''){
                        i++;
                        $('.claim_no').html('Claim no field is required.');
                    }
                    if(contact_person == ''){
                        i++;
                        $('.contact_person').html('Contact person field is required.')
                    }
                    if(contact_person_no == 0){
                        i++;
                        $('.contact_person_no').html('Contact person no field is required.');
                    }
                    if($('#PaymentType').val() == 2){
                        if(cheque_date == ''){
                            i++;
                            $('.cheque_date').html('Cheque Date field is required.');
                        }
                        if(bank_name == ''){
                            i++;
                            $('.bank_name').html('Bank name field is required.');
                        }
                    }
                    if($('#PaymentType').val() == 3){
                        if(transaction_no == ''){
                            i++;
                            $('.transaction_no').html('Transaction No field is required.');
                        }
                    }

                    if(i == 0){
                        myDropzone.processQueue();
                    }
                }else{
                    $('#formDropzone').submit();
                }
            }
        });
    </script>
@endsection
