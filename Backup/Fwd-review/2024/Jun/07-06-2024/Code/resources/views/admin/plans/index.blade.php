@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">Plan List
                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive p-3">
            <table id="example" class="table rwd-table mb-0">
                            <thead>
                                <tr>
                                    <th>Plan ID</th>
                                    <th>Plan Title</th>
                                    <th>Period Type</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($plans))
                                    @foreach ($plans as $plan)
                                        <tr>
                                            <td>{{ $plan->plan_id }}</td>
                                            <td>{{ $plan->plan_title }}</td>
                                            <td>{{ $plan->plan_period_type }}</td>
                                            <td>$ {{ number_format($plan->price, 2) }}</td>
                                            <td>
                                                @if ($plan->status == 1)
                                                    <h4 class="badge bg-success">Active</h4>
                                                @else
                                                    <h4 class="badge bg-danger">Deactive</h4>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="javascript:void(0)"
                                                    onclick="editPlan('{{ $plan->id }}')"><img
                                                        src="{{ url('/') }}/assets/Images/edit.png" alt=""
                                                        class="ed_btn me-2"></a>
                                                <a href="javascript:void(0);" data-id="{{ $plan->id }}"
                                                    class="delete-btn"><img
                                                        src="{{ url('/') }}/assets/Images/delete.png" alt=""
                                                        class="ed_btn"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
            </div>
        </div>
    </div>
    <div class="modal fade modal-lg" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update.plan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" id="edit_plan_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-floating">
                                <input type="text" name="plan_title" id="edit_plan_title" placeholder="Plan Title"
                                    class="form-control" required>
                                <label for="plan_title">Plan Title</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <select class="form-select" name="plan_period_type" required id="edit_plan_period_type">
                                    <option value="">Select Type</option>
                                    <option value="years">Years</option>
                                    <option value="months">Months</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="Days">Days</option>
                                </select>
                                <label for="plan_title">Plan Period Type</label>
                            </div>
                            <div class="col-md-6 form-floating mt-3">
                                <input type="number" name="price" id="edit_price" placeholder="Plan Price"
                                    class="form-control" required>
                                <label for="plan_title">Price</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <div class="col-md-6">
                                    <label for="Status" class="">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" id="status" />
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function editPlan(id) {
            $.ajax({
                'method': 'get',
                'url': "{{ route('edit.plan') }}",
                'data': {
                    'plan_id': id,
                },
                success: function(data) {
                    $('#edit_plan_id').val(data.id);
                    $('#edit_plan_title').val(data.plan_title);
                    $('#edit_price').val(data.price);
                    $('#edit_plan_period_type').val(data.plan_period_type);
                    if (data.status == "1") {
                        $('#status').attr("checked", 'true');
                    }
                    $('#editModal').modal('show');
                },
            })
        }
        $(document).on('click', '.delete-btn', function() {
            var plan_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure delete this plan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.plan', '') }}" + "/" + plan_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Plan has been deleted.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    toastr.success(data.message);
                                    top.location.href =
                                        "{{ route('setting.page') }}";
                                }
                            });
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    });
                }
            });
        });
    </script>
@endsection
