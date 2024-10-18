@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Role</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('role.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-8 col-xl-5">
                                <input type="text" name="role_name" class="form-control" placeholder="Enter Role Name">
                            </div>
                        </div>
                        <hr class="my-6">
                        <section class="roll-table-section">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Disable</th>
                                        <th>View</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Export</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $menu)
                                        <tr>
                                            <td data-column="First Name">{{ $menu->name }}</td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->id }}][disable]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->id }}][disable]" value="1" id="{{ $menu->id }}_disable" />
                                                <label for="{{ $menu->id }}_disable">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->id }}][view]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->id }}][view]" value="1" id="{{ $menu->id }}_view" />
                                                <label for="{{ $menu->id }}_view">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->id }}][add]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->id }}][add]" value="1" id="{{ $menu->id }}_add" />
                                                <label for="{{ $menu->id }}_add">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->id }}][edit]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->id }}][edit]" value="1" id="{{ $menu->id }}_edit" />
                                                <label for="{{ $menu->id }}_edit">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->id }}][delete]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->id }}][delete]" value="1" id="{{ $menu->id }}_delete" />
                                                <label for="{{ $menu->id }}_delete">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->id }}][export]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->id }}][export]" value="1" id="{{ $menu->id }}_export" />
                                                <label for="{{ $menu->id }}_export">Toggle</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </section>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a type="button" class="btn btn-sm btn-neutral" href="{{ route('role.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                        </div>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $('form').on('submit', function(e) {
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
    </script>
@endsection
