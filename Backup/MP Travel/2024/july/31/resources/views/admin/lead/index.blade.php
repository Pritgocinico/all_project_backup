@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Leads</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '18')->first()->status == 2)
                            <a href="{{ route('leads.create') }}" class="btn btn-sm btn-primary"><i
                                    class="bi bi-plus-lg me-2"></i>
                                New Lead</a>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lead</th>
                            <th>Investment Type</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $key=>$lead)
                            <tr>
                                <td>{{ $leads->firstItem() + $key }}</td>
                                <td><a href="{{ route('leads.show', $lead->id) }}">{{ $lead->lead_id }}</a></td>
                                <td>{{ ucfirst($lead->invest_type) }}</td>
                                <td>{{Utility::convertDmyAMPMFormat($lead->created_at)}}</td>
                                <td class="text-end">
                                    @if (collect($accesses)->where('menu_id', '20')->first()->status == 2)
                                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                                aria-expanded="false"><button type="button"
                                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                        class="bi bi-three-dots"></i></button></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('leads.edit', $lead->id) }}"><i
                                                        class="bi bi-pencil me-3"></i>Edit lead</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="deletelead({{ $lead->id }})"><i
                                                        class="bi bi-trash me-3"></i>Delete Lead </a>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $leads->links() }}
                </div>
            </div>
        </main>
        
    </div>
@endsection
@section('script')
    <script>
        function deletelead(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this Lead?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leads.destroy', '') }}" + "/" + id,
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
