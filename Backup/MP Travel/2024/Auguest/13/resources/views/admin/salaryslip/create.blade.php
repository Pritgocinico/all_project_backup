@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Salary Slip</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('salary-slip.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="container mt-6">
                            <div id="deduction-container">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-4 col-xl-4">
                                        <label class="form-label mb-0">Employee </label>
                                        <select name="emp_id[]" class="form-control" id="employee">
                                            <option value="">Select Employee</option>
                                            @foreach ($userList as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('emp_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-3">
                                    
                                    <div class="col-md-2"><label class="form-label mb-0">Deduction Type</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="deduction_type[]" class="form-control"
                                            placeholder="Enter Type" value="">
                                        @error('deduction_type')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Deduction Amount</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="amount[]" class="form-control"
                                            placeholder="Enter Amount" value="">
                                        @error('amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-primary" id="add-deduction"><i
                                            class="fas fa-plus"></i> Add Deduction</button>
                                </div>
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('salary-slip.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#add-deduction').click(function() {
                var newDeduction = `
                    <div class="row align-items-center g-3 mt-3 deduction-row">
                        <div class="col-md-2"><label class="form-label mb-0">Deduction Type</label></div>
                        <div class="col-md-3 col-xl-3">
                            <input type="text" name="deduction_type[]" class="form-control" placeholder="Enter Type" value="">
                            @error('deduction_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Deduction Amount</label></div>
                        <div class="col-md-3 col-xl-3">
                            <input type="number" name="amount[]" class="form-control" placeholder="Enter Amount" value="">
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 col-xl-2 text-end">
                            <button type="button" class="btn btn-danger remove-deduction"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `;
                $('#deduction-container').append(newDeduction);
            });

            // Event delegation to handle dynamically added delete buttons
            $(document).on('click', '.remove-deduction', function() {
                $(this).closest('.deduction-row').remove();
            });
        });
    </script>
@endsection
