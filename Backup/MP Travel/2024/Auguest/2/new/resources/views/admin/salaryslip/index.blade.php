@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Salary Slip Lists</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-primary" data-bs-target="#depositLiquidityModal"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                New Salary Slip</a>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Working Days</th>
                            <th>Present Days</th>
                            <th>Payble Salary</th>
                            <th>Leave</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salarySlipList as $key=>$salary)
                            <tr>
                                <td>{{ $salarySlipList->firstItem() + $key }}</td>
                                <td>
                                    @if($salary->employeeDetail && collect($accesses)->where('menu_id', '5')->first()->status == 2)
                                        <a href="{{ route('user.show', $salary->employeeDetail->id) }}">
                                            {{$salary->employeeDetail->name }}</a>
                                    @else
                                        {{'-'}}
                                    @endif
                                </td>
                                <td>{{ $salary->month }}</td>
                                <td>{{ $salary->year }}</td>
                                <td>{{ $salary->total_working_days }}</td>
                                <td>{{ $salary->present_days }}</td>
                                <td>{{ $salary->payable_salary }}</td>
                                <td>{{ $salary->leave }}</td>
                                <td>
                                    @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                                        <a href="#" onclick="generateSlip({{ $salary->id }})"
                                            class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-2"></i>
                                            Generate</a>
                                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                                aria-expanded="false"><button type="button"
                                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                        class="bi bi-three-dots"></i></button></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item"
                                                    href="{{ route('salary-slip.edit', $salary->id) }}"><i
                                                        class="bi bi-pencil me-3"></i>Edit Salary Slip</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="deleteSalarySlip({{ $salary->id }})"><i
                                                        class="bi bi-trash me-3"></i>Delete Salary Slip</a>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $salarySlipList->links() }}
                </div>
            </div>
        </main>
    </div>
    <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Generate Salary Slip</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="addForm" action="{{ route('salary-slip.store') }}">
                    @csrf
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2">
                                    <label class="form-label">Month</label>
                                </div>
                                <div class="col-md-10 col-xl-10">
                                    <select class="form-select" name="month" id="month" required>
                                        <option value="">Select Month</option>
                                        @foreach ($pastMonth as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function generateSlip(id) {
            $.ajax({
                url: '{{ route('generate-salary-slip') }}',
                type: 'POST',
                data: {
                    'id': id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    const link = document.createElement('a');
                    link.href = response.download_url;
                    link.download = '';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseText);
                }
            });
        }

        function deleteSalarySlip(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Salary Slip?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('salary-slip.destroy', '') }}" + "/" + id,
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
