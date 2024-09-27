@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border  justify-content-between d-flex">
            <div>
                <h4>Claim No: {{$claim->claim_no}}</h4>
                <h4 class="mt-2">Policy No: {{$policy->policy_no}}</h4>
            </div>
            <button type="button" class="btn btn-primary align-items-center d-none d-md-flex" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                     <i class="fa fa-plus"></i> Add Remark</button>
            </div>
        <div class="card-body">
         <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Remark Date</th>
                        <th>Remark</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($remarks))
                        @foreach ($remarks as $remark)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{date('d-m-Y',strtotime($remark->remark_date))}}</td>
                                <td>{{$remark->remark}}</td>
                                <td>
                                    @if (!blank($users))
                                        @foreach ($users as $item)
                                            @if ($item->id == $remark->created_by)
                                                {{$item->name}}
                                            @endif
                                        @endforeach
                                    @endif
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
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="staticBackdropLabel">Add New Remark</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
                <label for="Remark" class="form-label">Remark </label>
                <textarea class="form-control" name="remark" id="Remark" value="{{old('remark')}}" placeholder=""></textarea>
                @if ($errors->has('remark'))
                    <span class="text-danger">{{ $errors->first('remark') }}</span>
                @endif
                <span class="text-danger remark"></span>
            </div>
            <div class="col-md-12 mt-3">
                <label for="RemarkDate" class="form-label">Remark Date *</label>
                <input type="date" class="form-control" name="remark_date" id="RemarkDate" value="{{old('remark_date')}}" placeholder="">
                @if ($errors->has('remark_date'))
                    <span class="text-danger">{{ $errors->first('remark_date') }}</span>
                @endif
                <span class="text-danger remark_date"></span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary addRemark">Submit</button>
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
        $(document).on('click','.addRemark',function(){
            var remark = $('#Remark').val();
            var date = $('#RemarkDate').val();
            var claim_id = "{{$claim->id}}";
            if(remark == ''){
                $('.remark').html('Remark field is required.');
            }
            if(date == ''){
                $('.remark_date').html('Please enter date.');
            }
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.add.remark.data") }}',
                data: {"_token": "{{ csrf_token() }}",'id': claim_id,'remark':remark,'date':date},
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
    });
</script>
@endsection
