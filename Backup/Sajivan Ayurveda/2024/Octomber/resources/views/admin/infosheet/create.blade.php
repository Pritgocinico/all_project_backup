@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Incentive</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('incentive.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="id" value="{{ $infosheet->id ?? '' }}">
                        <div class="row align-items-center gap-3 mt-6">

                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Title <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="title" class="form-control" placeholder="Enter Title"
                                    value="{{ old('title') }}">
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Incentive <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="file" name="incentive_doc" class="form-control" placeholder="Upload Incentive">
                                @error('incentive_doc')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>

                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Employee <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="employee" class="form-control" id="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> 
                        
                        <div class="col-md-6 row align-items-center g-3 ">

                            <div class="col-md-4"><label class="form-label mb-0">Date <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="date" name="incentive_date" id="incentive_date" class="form-control">
                                @error('incentive_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> 
                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Amount <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="amount" class="form-control" placeholder="Enter Amount"
                                    value="{{ old('amount') }}">
                                @error('amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Description</label></div>
                            <div class="col-md-8 col-xl-6">
                                <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        </div>
                        
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('incentive.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                        </div>
                    </form>
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
