@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border  justify-content-between d-flex">
            <h4>Policy No: {{$policy->policy_no}}</h4>
            <a href="{{route('admin.add.claim',$id)}}" class="btn btn-primary align-items-center d-none d-md-flex"><span class="fs-5 me-2">+</span>Add Claim</a>
            </div>
        <div class="card-body">
          <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Claim No</th>
                        {{-- <th>Policy No</th> --}}
                        <th>Contact Person</th>
                        <th>Surveyar</th>
                        <th>Claim Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($claims))
                        @foreach ($claims as $claim)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$claim->claim_no}}</td>
                                {{-- <td>{{$claim->policy->policy_no}}</td> --}}
                                <td>{{$claim->contact_person}}</td>
                                <td>
                                    {{$claim->surveyar_name}}
                                </td>
                                <td>
                                    @if ($claim->claim_status == 1)
                                        <span class="badge bg-success">Open</span>
                                    @elseif ($claim->claim_status == 2)
                                        <span class="badge bg-info">Close</span>
                                    @else
                                        <span class="badge bg-warning">Repuidated</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="action">
                                    <li class="edit"> <a href="{{route('admin.claim.remarks',$claim->id)}}"><i class="icon-comment"></i></a> </li>
                                    <li class="edit"> <a href="{{route('admin.view_claim',$claim->id)}}"><i class="icon-eye"></i></a></li>
                                    <li class="edit"> <a href="{{route('admin.edit_claim',$claim->id)}}"><i class="icon-pencil-alt"></i></a></li>
                                    <li class="delete"> <a href="javascript:void(0);" data-id="{{ $claim->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
                                    </ul>
                                </td>
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
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $(document).on('click','.delete-btn',function(){
                var user_id = $(this).attr('data-id');
                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{route('delete.claim', '')}}"+"/"+user_id,
                        type : 'GET',
                        dataType:'json',
                        success : function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Claim has been deleted.",
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
