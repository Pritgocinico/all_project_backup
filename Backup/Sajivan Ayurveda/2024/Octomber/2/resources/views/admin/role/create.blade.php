@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Role</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
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
                                        <th>View</th>
                                        <th>Export</th>


                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td data-column="First Name">Dashboard</td>
                                        <td><input type="checkbox" id="switch1" /><label for="switch1">Toggle</label></td>
                                        <td><input type="checkbox" id="switch2" /><label for="switch2">Toggle</label></td>
                                        <td><input type="checkbox" id="switch3" /><label for="switch3">Toggle</label></td>
                                        <td><input type="checkbox" id="switch4" /><label for="switch4">Toggle</label></td>
                                        <td><input type="checkbox" id="switch5" /><label for="switch5">Toggle</label></td>
                                        <td><input type="checkbox" id="switch6" /><label for="switch6">Toggle</label></td>
                                        <td><input type="checkbox" id="switch7" /><label for="switch7">Toggle</label></td>
                                       
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Department</td>
                                        <td><input type="checkbox" id="switch8" /><label for="switch8">Toggle</label></td>
                                        <td><input type="checkbox" id="switch9" /><label for="switch9">Toggle</label></td>
                                        <td><input type="checkbox" id="switch10" /><label for="switch10">Toggle</label></td>
                                        <td><input type="checkbox" id="switch11" /><label for="switch11">Toggle</label></td>
                                        <td><input type="checkbox" id="switch12" /><label for="switch12">Toggle</label></td>
                                        <td><input type="checkbox" id="switch13" /><label for="switch13">Toggle</label></td>
                                        <td><input type="checkbox" id="switch14" /><label for="switch14">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Designation</td>
                                        <td><input type="checkbox" id="switch15" /><label for="switch15">Toggle</label></td>
                                        <td><input type="checkbox" id="switch16" /><label for="switch16">Toggle</label></td>
                                        <td><input type="checkbox" id="switch17" /><label for="switch17">Toggle</label></td>
                                        <td><input type="checkbox" id="switch18" /><label for="switch18">Toggle</label></td>
                                        <td><input type="checkbox" id="switch19" /><label for="switch19">Toggle</label></td>
                                        <td><input type="checkbox" id="switch20" /><label for="switch20">Toggle</label></td>
                                        <td><input type="checkbox" id="switch21" /><label for="switch21">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Role</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">User</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>

                                      <tr>
                                        <td data-column="First Name">Info sheet</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>

                                      <tr>
                                        <td data-column="First Name">Leave</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Holiday</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Ticket</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Attendance</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Daily attendance</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>

                                      <tr>
                                        <td data-column="First Name">Certificate</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr><tr>
                                        <td data-column="First Name">Pay salary</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Lead</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Follow up</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Service Preference</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Customer</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>

                                      <tr>
                                        <td data-column="First Name">Product</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Category</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                      <tr>
                                        <td data-column="First Name">Log</td>
                                        <td><input type="checkbox" id="switch22" /><label for="switch22">Toggle</label></td>
                                        <td><input type="checkbox" id="switch23" /><label for="switch23">Toggle</label></td>
                                        <td><input type="checkbox" id="switch24" /><label for="switch24">Toggle</label></td>
                                        <td><input type="checkbox" id="switch25" /><label for="switch25">Toggle</label></td>
                                        <td><input type="checkbox" id="switch26" /><label for="switch26">Toggle</label></td>
                                        <td><input type="checkbox" id="switch27" /><label for="switch27">Toggle</label></td>
                                        <td><input type="checkbox" id="switch28" /><label for="switch28">Toggle</label></td>
                                      </tr>
                                    </tbody>
                                  </table>
                         
                        </section>
                       
                                
                                
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
