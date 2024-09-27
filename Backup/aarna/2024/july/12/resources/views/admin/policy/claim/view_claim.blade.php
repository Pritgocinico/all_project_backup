@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive custom-scrollbar">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td>Claim No</td>
                            <td class="fw-bold">{{$claim->claim_no}}</td>
                        </tr>
                        <tr>
                            <td>Policy Id</td>
                            <td class="fw-bold">
                                {{$policy->policy_no}}
                            </td>
                        </tr>
                        <tr>
                            <td>Claim Date</td>
                            <td>
                                {{($claim->claim_date != '')?date('d-m-Y',strtotime($claim->claim_date)):""}}
                            </td>
                        </tr>
                        <tr>
                            <td>Contact person</td>
                            <td>{{$claim->contact_person}}</td>
                        </tr>
                        <tr>
                            <td>Contact Person No</td>
                            <td>{{$claim->contact_person_no}}</td>
                        </tr>
                        <tr>
                            <td>Surveyar Name</td>
                            <td>{{$claim->surveyar_name}}</td>
                        </tr>
                        <tr>
                            <td>Surveyar Number</td>
                            <td>{{$claim->surveyar_no}}</td>
                        </tr>
                        <tr>
                            <td>Surveyar Email</td>
                            <td>{{$claim->surveyar_email}}</td>
                        </tr>
                        <tr>
                            <td>Repairing Location</td>
                            <td>{{$claim->repairing_location}}</td>
                        </tr>
                        <tr>
                            <td>Claim Status</td>
                            <td>
                                @if ($claim->claim_status == 1)
                                    <span class="badge bg-success">Open</span>
                                @elseif ($claim->claim_status == 2)
                                    <span class="badge bg-info">Close</span>
                                @else
                                    <span class="badge bg-warning">Repuidated</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Claim Type</td>
                            <td>
                                @if ($claim->claim_type == 1)
                                    <span class="badge bg-success">OWN DAMAGE</span>
                                @elseif ($claim->claim_type == 2)
                                    <span class="badge bg-info">THIRD PARTY</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status Text</td>
                            <td>{{$claim->status_text}} &nbsp; {{$claim->status_date}}</td>
                        </tr>
                        <tr>
                            <td>Payment Type</td>
                            <td>
                                @if ($claim->payment_type == 1)
                                    Cash
                                @elseif($claim->payment_type == 2)
                                    Cheque
                                @else
                                    Online
                                @endif
                            </td>
                        </tr>
                        @if($claim->payment_type == 2)
                            <tr>
                                <td>Cheque No</td>
                                <td>{{$claim->cheque_no}}</td>
                            </tr>
                            <tr>
                                <td>Cheque No</td>
                                <td>{{$claim->cheque_date}}</td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td>{{$claim->bank_name}}</td>
                            </tr>
                        @endif
                        @if($claim->payment_type == 3)
                            <tr>
                                <td>Transaction No</td>
                                <td>{{$claim->transaction_no}}</td>
                            </tr>
                        @endif
                        @if(!blank($documents))
                            <tr>
                                <td>Claim Documents</td>
                                <td>
                                    @foreach ($documents as $doc)
                                        <a href="{{url('/')}}/claim_attachment/{{$doc->file}}" target="_new" ><img src="{{url('/')}}/assets/Images/docs.png" width="40px" alt="">
                                        {{$doc->file_name}}
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
