@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border  justify-content-between d-flex">
            <h4>Claim</h4>
            </div>
        <div class="card-body">
          <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Claim No</th>
                        <th>Customer Name</th>
                        <th>Vehicle Chassis No</th>
                        <th>Vehicle Make</th>
                        <th>Vehicle Model</th>
                        <th>Vehicle Registration No</th>
                        <th>Policy Type</th>
                        <th>Claim Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($claims))
                        @foreach ($claims as $claim)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td><a class="table-url"
                                        href="{{ route('admin.view_claim', $claim->id) }}">{{ $claim->claim_no }}</a>
                                </td>
                                <td>{{isset($claim->policy) && isset($claim->policy->customers) ? $claim->policy->customers->name :"-"}}</td>
                                <td>{{ $claim->policy->vehicle_chassis_no }}</td>
                                <td>{{ $claim->policy->vehicle_make }}</td>
                                <td>{{ $claim->policy->vehicle_model }}</td>
                                <td>{{ $claim->policy->vehicle_registration_no }}</td>
                                <td>
                                    @if (!is_null($claim->policy->sub_category))
                                        @php $sub_category = App\Models\Category::firstWhere('id', $claim->policy->sub_category) @endphp
                                        @if (!is_null($sub_category))
                                            {{ $sub_category->name }}
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($claim->claim_status == 1)
                                        <span class="badge badge-success">Open</span>
                                    @elseif ($claim->claim_status == 2)
                                        <span class="badge badge-info">Close</span>
                                    @else
                                        <span class="badge badge-warning">Repuidated</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="action">
                                        <li class="edit"> <a
                                                href="{{ route('admin.claim.remarks', $claim->id) }}"><i
                                                    class="icon-comment"></i></a> </li>
                                        <li class="edit"> <a
                                                href="{{ route('admin.view_claim', $claim->id) }}"><i
                                                    class="icon-eye"></i> </a> </li>
                                        <li class="edit"> <a
                                                href="{{ route('admin.edit_claim', $claim->id) }}"><i
                                                    class="icon-pencil-alt"></i></a> </li>
                                        <li class="delete"><a href="javascript:void(0);"
                                                data-id="{{ $claim->id }}"
                                                class="delete-btn"><i class="icon-trash"></i></a>
                                        </li>
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
