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
                            <a href="{{ route('salary-slip.create') }}" class="btn btn-sm btn-primary"
                                class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-2"></i>
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
                                <td>{{ isset($salary->employeeDetail) ? $salary->employeeDetail->name : '-' }}</td>
                                <td>{{ $salary->month }}</td>
                                <td>{{ $salary->year }}</td>
                                <td>{{ $salary->total_working_days }}</td>
                                <td>{{ $salary->present_days }}</td>
                                <td>{{ $salary->payable_salary }}</td>
                                <td>{{ $salary->leave }}</td>
                                <td>
                                    @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                                        @if ($salary->file_path == null)
                                            <a href="#" onclick="generateSlip({{ $salary->id }})"
                                                class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-2"></i>
                                                Generate</a>
                                        @else
                                            <a href="{{asset('storage/salary_slip/'.$salary->file_path)}}" download
                                                class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-2"></i>
                                                Generate</a>
                                        @endif
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
@endsection
@section('script')
    <script>
        function generateSlip(id) {
            $.ajax({
                url: '{{ route("generate-salary-slip") }}',
                type: 'POST',
                data: {
                    'id':id,
                    _token:"{{csrf_token()}}",
                },
                success: function(response) {
                    if (response.status === 'success') {
                        const link = document.createElement('a');
                        link.href = response.download_url;
                        link.download = '';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('An error occurred. Please try again.');
                }
            });
        }
    </script>
@endsection
