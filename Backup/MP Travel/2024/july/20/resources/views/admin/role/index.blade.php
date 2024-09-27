@extends('admin.partials.header', ['active' => 'user'])
@section('content')
<div class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
    <main class="container-fluid p-0">
        <div class="px-6 px-lg-7 pt-8 border-bottom">
            <div class="d-flex align-items-center mb-5">
                <h1>Roles</h1>
                <div class="hstack gap-2 ms-auto">
                    @if (collect($accesses)->where('menu_id', '4')->first()->status == 2)
                    <a href="{{ route('role.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-2"></i>
                        New Role</a>
                    @endif
                </div>
            </div>
        </div>
        <div>
            <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Permission</th>
                        <th>Created At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roleList as $key=>$role)
                    <tr>
                        <td>{{ $roleList->firstItem() + $key }}</td>
                        <td>{{ $role->name }}</td>

                        <td>
                            @php
                            $counter = 0;
                            @endphp
                            @foreach ($role->access as $access)
                            <b>{{ Str::ucfirst($access->menu->name) . ' - ' }}</b>
                            @php
                            $status = 'All';
                            if ($access->status == 0) {
                            $status = 'Disabled';
                            } elseif ($access->status == 1) {
                            $status = 'View';
                            }
                            @endphp
                            {{ $status }},
                            @php
        $counter++;
    @endphp

    @if ($counter % 4 == 0)
        <br>
    @endif
                            @endforeach
                        </td>
                        <td>{{ Utility::convertDmyAMPMFormat($role->created_at) }}</td>
                        <td class="text-end">
                            @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                            <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><button type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i class="bi bi-three-dots"></i></button></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('role.edit', $role->id) }}"><i class="bi bi-pencil me-3"></i>Edit Role</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="deleteRole({{ $role->id }})"><i class="bi bi-trash me-3"></i>Delete Role </a>
                                </div>
                            </div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No Data Available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-end me-2 mt-2">
                {{ $roleList->links() }}
            </div>
        </div>
    </main>
</div>
@endsection
@section('script')
<script>
    function deleteRole(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure the delete this role?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('role.destroy', '') }}" + "/" + id,
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