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
                    <form action="{{ route('salary-slip.update', $salaryDetail->id) }}" enctype="multipart/form-data"
                        method="POST">
                        @method('PUT')
                        @csrf
                        <div class="container mt-6">
                            <div id="deduction-container">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Employee</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="emp_id" class="form-control"
                                            value="{{ isset($salaryDetail->employeeDetail) ? $salaryDetail->employeeDetail->name : '-' }}"
                                            readonly>
                                        @error('emp_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Present Days</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="present_day" class="form-control"
                                            value="{{ $salaryDetail->present_days ??0}}">
                                        @error('present_day')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Absent Day</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="absent_day" class="form-control"
                                            value="{{ $salaryDetail->absent_day ??0}}">
                                        @error('absent_day')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Total Overtime Hour</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="total_over_time" class="form-control"
                                            value="{{ $salaryDetail->total_over_time ??0}}">
                                        @error('total_over_time')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Total Overtime Amount</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="over_time_amount" class="form-control"
                                            value="{{ $salaryDetail->over_time_amount ??0}}">
                                        @error('over_time_amount')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
            
                                </div>
                                <div class="row align-items-center g-3 mt-3">
                                    @foreach ($salaryDetail->deductionDetail as $key => $deduct)
                                        <input type="hidden" name="deduction_id[]" value="{{ $deduct->id }}">
                                        <div class="col-md-2"><label class="form-label mb-0">Deduction Type</label></div>
                                        <div class="col-md-3 col-xl-3">
                                            <input type="text" name="deduction_type[]" class="form-control"
                                                placeholder="Enter Type" value="{{ $deduct->deduction_type }}">
                                            @error('deduction_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Deduction Amount</label></div>
                                        <div class="col-md-3 col-xl-3">
                                            <input type="number" name="amount[]" class="form-control"
                                                placeholder="Enter Amount" value="{{ $deduct->deduction_amount }}">
                                            @error('amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2 col-xl-2 text-end">
                                            <button type="button" class="btn btn-danger remove-deduction"
                                                data-type="remove" data-id="{{ $deduct->id }}"><i
                                                    class="bi bi-trash"></i></button>
                                        </div>
                                    @endforeach
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
        $(document).ready(function() {
            $('#add-deduction').click(function() {
                var newDeduction = `
                    <div class="row align-items-center g-3 mt-3 deduction-row">
                    <input type="hidden" name="deduction_id[]" value="" data-type="button">
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
                            <button type="button" class="btn btn-danger remove-deduction" data-type="button"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `;
                $('#deduction-container').append(newDeduction);
            });

            // Event delegation to handle dynamically added delete buttons
            $(document).on('click', '.remove-deduction', function() {
                var remove = $(this).data('type');
                if (remove == "button") {
                    $(this).closest('.deduction-row').remove();
                    return 1;
                }
                $.ajax({
                    url: "{{ route('deduction.delete') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(this).data('id'),
                    },
                    success: function(response) {
                        $(this).closest('.deduction-row').remove();
                    }
                })
            });
        });
    </script>
@endsection
