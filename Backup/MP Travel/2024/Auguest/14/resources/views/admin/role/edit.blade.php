@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Update Role</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
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
                        @foreach ($accessesForEditing as $access)
                            @if(isset($access->menu))
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label
                                            class="form-label mb-0">{{ Str::ucfirst($access->menu->name) }}</label></div>
                                    <div class="col-md-10">
                                        <input class="form-check-input" type="radio"
                                            name="menuAndAccessLevel[{{ $loop->index }}][{{ $access->menu->id }}]"
                                            id="{{ $access->menu->name }}_disabled" value="0" required
                                            {{ $access->status == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $access->menu->name }}_disabled">
                                            Disabled (All Permission)
                                        </label>
                                        <input class="form-check-input" type="radio"
                                            name="menuAndAccessLevel[{{ $loop->index }}][{{ $access->menu->id }}]"
                                            id="{{ $access->menu->name }}_view" value="1"
                                            {{ $access->status == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $access->menu->name }}_view">
                                            View (All Permission)
                                        </label>
                                        <input class="form-check-input" type="radio"
                                            name="menuAndAccessLevel[{{ $loop->index }}][{{ $access->menu->id }}]"
                                            id="{{ $access->menu->name }}_all" value="2"
                                            {{ $access->status == '2' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $access->menu->name }}_all">
                                            All (View, Add, Edit, Delete, Export Permission)
                                        </label>

                                        <input class="form-check-input" type="radio"
                                            name="menuAndAccessLevel[{{ $loop->index }}][{{ $access->menu->id }}]"
                                            id="{{ $access->menu->name }}_owm" value="3"
                                            {{ $access->status == '3' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $access->menu->name }}_owm">
                                            View (Only Own)
                                        </label>
                                    </div>
                                </div>
                                <hr class="my-6">
                            @endif
                        @endforeach
                        <div class="d-flexjustify-content-end gap-2">
                            <a type="button" class="btn btn-sm btn-neutral" href="{{ route('role.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark">Save</button>
                        </div>
                </main>
            </div>
        </main>
    </div>
@endsection
