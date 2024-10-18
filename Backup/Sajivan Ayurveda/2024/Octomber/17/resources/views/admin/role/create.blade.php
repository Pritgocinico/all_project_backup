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
                        
                                
                       
                        @foreach ($menus as $menu)
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label
                                        class="form-label mb-0 text-uppercase">{{ str_replace("_"," ",Str::ucfirst($menu->name)) }}</label></div>
                                <div class="col-md-10">
                                    <input class="form-check-input" type="radio"
                                        name="menuAndAccessLevel[{{ $loop->index }}][{{ $menu->id }}]"
                                        id="{{ $menu->name }}_disabled" value="0" required
                                        {{ old("menuAndAccessLevel[$loop->index][$menu->id]") == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $menu->name }}_disabled">
                                        Disabled (All Permission)
                                    </label>
                                    <input class="form-check-input" type="radio"
                                        name="menuAndAccessLevel[{{ $loop->index }}][{{ $menu->id }}]"
                                        id="{{ $menu->name }}_view" value="1"
                                        {{ old("menuAndAccessLevel[$loop->index][$menu->id]") == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $menu->name }}_view">
                                        View (All Permission)
                                    </label>
                                    <input class="form-check-input" type="radio"
                                        name="menuAndAccessLevel[{{ $loop->index }}][{{ $menu->id }}]"
                                        id="{{ $menu->name }}_all" value="2"
                                        {{ old("menuAndAccessLevel[$loop->index][$menu->id]") == '2' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $menu->name }}_all">
                                        All (View, Add, Edit, Delete Permission)
                                    </label>
                                    <input class="form-check-input" type="radio"
                                        name="menuAndAccessLevel[{{ $loop->index }}][{{ $menu->id }}]"
                                        id="{{ $menu->name }}_owm" value="3"
                                        {{ old("menuAndAccessLevel[$loop->index][$menu->id]") == '3' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $menu->name }}_owm">
                                        View (Only Own)
                                    </label>
                                </div>
                            </div>
                            <hr class="my-6">
                        @endforeach
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
