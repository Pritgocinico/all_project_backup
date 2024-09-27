@extends('admin.partials.header', ['active' => 'customer'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Customers</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '5')->first()->status == 2)
                            <a href="{{ route('customer.create') }}" class="btn btn-sm btn-primary"><i
                                    class="bi bi-plus-lg me-2"></i>
                                New Customers</a>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Department Name</th>
                            <th>Designation Name</th>
                            <th>Role Name</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customerList as $key=>$customer)
                            <tr>
                                <td>{{ $customerList->firstItem() + $key }}</td>
                                <td><a href="{{ route('user.show', $customer->id) }}">{{ $customer->name }}</a></td>
                                <td>
                                    {{ $customer->email }}
                                </td>
                                <td>
                                    {{ $customer->phone_number }}
                                </td>
                                <td>
                                    {{ isset($customer->departmentDetail) ? $customer->departmentDetail->name:"-" }}
                                </td>
                                <td>
                                    {{ isset($customer->designationDetail) ? $customer->designationDetail->name:"-" }}
                                </td>
                                <td>{{ isset($customer->roleDetail) ? ucfirst($customer->roleDetail->name) : '' }}</td>
                                <td>{{Utility::convertDmyAMPMFormat($customer->created_at)}}</td>
                                <td class="text-end">
                                    @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                                aria-expanded="false"><button type="button"
                                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                        class="bi bi-three-dots"></i></button></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('user.edit', $customer->id) }}"><i
                                                        class="bi bi-pencil me-3"></i>Edit Customers</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="deleteUser({{ $customer->id }})"><i
                                                        class="bi bi-trash me-3"></i>Delete Customers</a>
                                            </div>
                                        </div>
                                    @endif

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $customerList->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Employee?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
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
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
                }
            });
        }
    </script>
@endsection
