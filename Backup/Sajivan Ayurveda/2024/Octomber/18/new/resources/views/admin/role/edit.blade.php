@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Update Role</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('role.update',$role->id) }}" enctype="multipart/form-data" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-4 col-xl-5">
                                <input type="text" name="role_name" class="form-control" placeholder="Enter Role Name" value="{{$role->name}}">
                            </div>
                            <div class="col-md-4 col-xl-5">
                                <input type="text" name="role_name" class="form-control" placeholder="Enter Role Name" value="{{$role->id}}" disabled>
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center gap-3 mt-6" id="status_div">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Status</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="status"
                                            {{ $role->active == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
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
                                    @foreach ($accessesForEditing as $menu)
                                    @if(isset($menu->menu))
                                        <tr>
                                            <td data-column="First Name">{{ str_replace("_"," ",Str::ucfirst($menu->menu->name)) }}</td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->menu_id }}][disable]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->menu_id }}][disable]" value="1" id="{{ $menu->menu_id }}_disable" {{$menu->disable == 1 ? "checked" : ""}}/>
                                                <label for="{{ $menu->menu_id }}_disable">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->menu_id }}][view]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->menu_id }}][view]" value="1" id="{{ $menu->menu_id }}_view" {{$menu->view == 1 ? "checked" : ""}}/>
                                                <label for="{{ $menu->menu_id }}_view">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->menu_id }}][add]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->menu_id }}][add]" value="1" id="{{ $menu->menu_id }}_add" {{$menu->add == 1 ? "checked" : ""}}/>
                                                <label for="{{ $menu->menu_id }}_add">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->menu_id }}][edit]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->menu_id }}][edit]" value="1" id="{{ $menu->menu_id }}_edit" {{$menu->edit == 1 ? "checked" : ""}}/>
                                                <label for="{{ $menu->menu_id }}_edit">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->menu_id }}][delete]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->menu_id }}][delete]" value="1" id="{{ $menu->menu_id }}_delete" {{$menu->delete == 1 ? "checked" : ""}}/>
                                                <label for="{{ $menu->menu_id }}_delete">Toggle</label>
                                            </td>
                                            <td>
                                                <input type="hidden" name="menu[{{ $menu->menu_id }}][export]" value="0">
                                                <input type="checkbox" name="menu[{{ $menu->menu_id }}][export]" value="1" id="{{ $menu->menu_id }}_export" {{$menu->export == 1 ? "checked" : ""}}/>
                                                <label for="{{ $menu->menu_id }}_export">Toggle</label>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </section>
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
        $('form').on('submit',function(e){
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
    </script>
@endsection
