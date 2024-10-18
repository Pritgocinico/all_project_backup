@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Product</h1>
                    </div>
                </div>
            </div>
            <div
            
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('product.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <div class="row align-items-center gap-3 mt-6">

                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Medicine Type <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="medicine_type" class="form-control" id="medicine_type">
                                    <option value="">Select Option</option>
                                    <option value="powder" {{ old('medicine_type') == 'powder' ? 'selected' : '' }}>Powder</option>
                                    <option value="tablet" {{ old('medicine_type') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="capsule" {{ old('medicine_type') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                </select>
                                @error('medicine_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                            <!-- Powder Unit -->
                            <div class="col-md-4 d-none" id="powder_div_label"><label class="form-label mb-0">Unit of Powder <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6 d-none" id="powder_div">
                                <select name="powder_unit" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="100_gm" {{ old('powder_unit') == '100_gm' ? 'selected' : '' }}>100 gm</option>
                                </select>
                                @error('powder_unit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Tablet Unit -->
                            <div class="col-md-4 d-none" id="tab_div_label"><label class="form-label mb-0">Unit of Tab <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6 d-none" id="tab_div">
                                <select name="tablet_unit" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="60_tab" {{ old('tablet_unit') == '60_tab' ? 'selected' : '' }}>60 tab</option>
                                </select>
                                @error('tablet_unit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <!-- Capsule Unit -->
                            <div class="col-md-4 d-none" id="capsule_div_label"><label class="form-label mb-0">Unit of Capsule <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6 d-none" id="capsule_div">
                                <select name="capsule_unit" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="60_tab" {{ old('capsule_unit') == '60_tab' ? 'selected' : '' }}>60 tab</option>
                                </select>
                                @error('capsule_unit')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        </div>
                        
                        <div class="row align-items-center gap-3 mt-6">
                            
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">HSM/SAC <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="hsm_sac" class="form-control" placeholder="Enter Product HSM/SAC"
                                    value="{{ old('hsm_sac') }}">
                                @error('hsm_sac')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>  
                            </div> 
                            <div class="col-md-6 row align-items-center g-3 ">

                            <div class="col-md-4"><label class="form-label mb-0">Name <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="name" class="form-control" placeholder="Enter Product Name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div> 
                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Batch Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="batch_number" class="form-control" placeholder="Enter Batch Number"
                                    value="{{ old('batch_number') }}">
                                @error('batch_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Price <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="price" class="form-control" placeholder="Enter Price" min="0"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        </div>
                        <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Without Tax Price</label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="without_tax_price" class="form-control" placeholder="Enter Price" min="0"
                                    value="{{ old('without_tax_price') }}">
                                @error('without_tax_price')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Mfg. Lic. Number <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="mfg_lic_number" class="form-control" placeholder="Enter Mfg. Lic. Number"
                                    value="{{ old('mfg_lic_number') }}">
                                @error('mfg_lic_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Mfg. Date <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="mfg_date" id="MFGdate" class="form-control" placeholder="Enter Mfg. Date"
                                    value="{{ old('mfg_date') }}">
                                @error('mfg_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Stock <span class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="stock" class="form-control" placeholder="Enter Stock" min="0"
                                    value="{{ old('stock') }}">
                                @error('stock')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6" id="status_div">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Status</label></div>
                            <div class="col-md-8 col-xl-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                </div>
                            </div>
                            </div>
                        </div>
                        
                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('product.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
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
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#MFGdate", {
                dateFormat: "Y-m", // Year and month format
                altInput: true,     // Show formatted date
                altFormat: "F Y",   // Format: Month Full Name and Year
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, // Show month in short format
                        dateFormat: "Y-m", // Actual value format
                        altFormat: "F Y",  // Displayed format in the input
                        theme: "light" // theme for the picker
                    })
                ]
            });
        });
        $(document).ready(function() {
            function showHideUnitFields() {
                var selectedType = $('#medicine_type').val();
                if (selectedType === 'powder') {
                    $('#powder_div, #powder_div_label').removeClass('d-none');
                    $('#tab_div, #tab_div_label').addClass('d-none');
                    $('#capsule_div, #capsule_div_label').addClass('d-none');
                } else if (selectedType === 'tablet') {
                    $('#tab_div, #tab_div_label').removeClass('d-none');
                    $('#powder_div, #powder_div_label').addClass('d-none');
                    $('#capsule_div, #capsule_div_label').addClass('d-none');
                } else if (selectedType === 'capsule') {
                    $('#capsule_div, #capsule_div_label').removeClass('d-none');
                    $('#powder_div, #powder_div_label').addClass('d-none');
                    $('#tab_div, #tab_div_label').addClass('d-none');
                } else {
                    // Hide all unit fields if no valid option is selected
                    $('#powder_div, #powder_div_label').addClass('d-none');
                    $('#tab_div, #tab_div_label').addClass('d-none');
                    $('#capsule_div, #capsule_div_label').addClass('d-none');
                }
            }

            // Trigger on page load to handle old values after validation
            showHideUnitFields();

            // Trigger on change of the medicine_type field
            $('#medicine_type').on('change', function() {
                showHideUnitFields();
            });
        });


        $('form').on('submit',function(e){
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
        $('.product_image').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#product_image_preview').html(resultString);

        })
    </script>
@endsection
