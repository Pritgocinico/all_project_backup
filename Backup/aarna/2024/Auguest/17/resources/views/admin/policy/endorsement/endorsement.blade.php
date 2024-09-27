@extends('admin.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header justify-content-between d-flex card-no-border">
            <h4>Policy No: {{$policy->policy_no}}</h4>
            <a href="{{route('admin.add.endorsement',$id)}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
        </div>           
        <div class="card-body">
          <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Endorsement Details</th>
                        <th>Supporting Documents</th>
                        <th>Endorsement Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($endorsements))
                        @foreach ($endorsements as $endorsement)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$endorsement->details}}</td>
                                <td>{{$endorsement->supporting_documents}}</td>
                                <td>
                                    {{$endorsement->created_at}}
                                </td>
                                <td>
                                    <ul class="action">
                                        <li class="edit"><a href="{{route('admin.edit_endorsement',$endorsement->id)}}"><i class="icon-pencil-alt"></i></a></li>
                                        <li class="delete"><a href="javascript:void(0);" data-id="{{ $endorsement->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
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
                    url : "{{route('delete.endorsement', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Endorsement has been deleted.",
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
