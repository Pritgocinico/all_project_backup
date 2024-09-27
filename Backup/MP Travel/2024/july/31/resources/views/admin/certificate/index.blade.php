@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Certificate</h1>
                </div>
                @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
                    <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" id="generate-certificate-tab">Generate Certificate</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="certificate-list-tab">Certificate List</a>
                        </li>
                    </ul>
                @elseif(collect($accesses)->where('menu_id', '14')->first()->status == 1)
                    <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" id="certificate-list-tab">Certificate List</a>
                        </li>
                    </ul>
                @endif
            </div>
        
            <div class="container">
                <div>
                    <!-- Form Section -->
                    <div id="generate-certificate-section" @if(collect($accesses)->where('menu_id', '14')->first()->status == 1) style="display: none;" @else style="" @endif>
                        <main class="container-fluid px-6 pb-10">
                            <form id="certificateForm">
                                @csrf
                                <input type="hidden" name="id" value="{{ $certificateList->id ?? '' }}">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Title</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="title" class="form-control" placeholder="Enter Title"
                                            value="{{ old('title', $certificateList->name ?? '') }}">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">Employee</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="emp_id" class="form-control" id="employee">
                                            <option value="">Select Employee</option>
                                            @foreach ($employeeList as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('emp_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Director</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="director" class="form-control" placeholder="Enter Director"
                                            value="{{ old('director', $certificateList->name ?? '') }}">
                                        @error('director')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">Manager</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="manager" class="form-control" placeholder="Enter Manager"
                                            value="{{ old('manager', $certificateList->name ?? '') }}">
                                        @error('manager')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ old('description', $certificateList->description ?? '') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="row align-items-center g-3 mt-6" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-6">
                                <div class="d-flexjustify-content-end gap-2">
                                    <a href="{{ route('certificate.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </div>
                            </form>
                        </main>
                    </div>

                    <!-- Table Section -->
                    <div id="certificate-list-section" @if(collect($accesses)->where('menu_id', '14')->first()->status == 1) style="" @else style="display: none;" @endif>
                        <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Employee</th>
                                    <th>Certificate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($certificateList as $key=>$certificate)
                                    <tr>
                                        <td>{{ $certificateList->firstItem() + $key }}</td>
                                        <td>{{ $certificate->title }}</td>
                                        <td>{{ $certificate->employee->name }}</td>
                                        <td> <a href="{{ Storage::url($certificate->file_path) }}" download><img src="{{ url('assets/img/user/file.png') }}" width="60px"></a> </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No Data Available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end me-2 mt-2">
                            {{ $certificateList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
<script>
    document.getElementById('generate-certificate-tab').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('generate-certificate-section').style.display = 'block';
        document.getElementById('certificate-list-section').style.display = 'none';
        document.getElementById('generate-certificate-tab').classList.add('active');
        document.getElementById('certificate-list-tab').classList.remove('active');
    });

    document.getElementById('certificate-list-tab').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('generate-certificate-section').style.display = 'none';
        document.getElementById('certificate-list-section').style.display = 'block';
        document.getElementById('generate-certificate-tab').classList.remove('active');
        document.getElementById('certificate-list-tab').classList.add('active');
    });

    $(document).ready(function() {
        $('#certificateForm').on('submit', function(e) {
            e.preventDefault();

            // Submit the form via AJAX
            $.ajax({
                url: '{{ route("certificate.store") }}',
                type: 'POST',
                data: $(this).serialize(),
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
        });
    });
</script>
@endsection
