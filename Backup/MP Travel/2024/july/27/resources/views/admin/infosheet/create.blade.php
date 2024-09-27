@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Info Sheet {{ isset($infosheet) ? 'Update' : 'Create' }}</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    @php
                        $route = route('info_sheet.store');
                        $method = 'POST';
                        if (isset($infosheet)) {
                            $route = route('info_sheet.update', $infosheet->id);
                            $method = 'PUT';
                        }
                    @endphp
                    <form action="{{ $route }}" enctype="multipart/form-data" method="POST">
                        @method($method)
                        @csrf
                        <input type="hidden" name="id" value="{{ $infosheet->id ?? '' }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Title</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Title"
                                    value="{{ old('name', $infosheet->name ?? '') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Info Sheet</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="file" name="info_sheet" class="form-control" placeholder="Upload Info Sheet">
                                @if (isset($infosheet->info_sheet))
                                    <a href="{{ Storage::url($infosheet->info_sheet) }}" target="_blank">View current file</a>
                                @endif
                                @error('info_sheet')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                            <div class="col-md-4 col-xl-4">
                                <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ old('description', $infosheet->description ?? '') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6" id="status_div">
                            <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                            <div class="col-md-10 col-xl-10">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" @if(isset($infosheet)) {{ old('status', $infosheet->status ?? false) ? 'checked' : '' }} @else checked @endif>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('info_sheet.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        
    </script>
@endsection
