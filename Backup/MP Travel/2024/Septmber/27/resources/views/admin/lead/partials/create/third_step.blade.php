<div id="third_step_div" style="display:  @if (old('invest_type') == '') none @endif">
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Assigned To<span class="text-danger">*</span></label></div>
        <div class="col-md-4 col-xl-4">
            <select name="assigned_to[]" class="form-control js-example-basic-single"
                id="assigned_to" multiple>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ in_array($user->id, old('assigned_to') ?? []) ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
            @error('assigned_to')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <span id="assigned_to_error" class="error_span"></span>
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Lead Amount (Optional)</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="number" name="lead_amount" class="form-control" id="lead_amount"
                placeholder="Enter Lead Amount (Optional)" value="{{ old('lead_amount') }}" min="0">
            @error('lead_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-item-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Department<span class="error_span">*</span></label></div>
        <div class="col-md-4">
            <select class="form-select" name="department" id="department">
                <option value="">Select Department</option>    
                @foreach ($departmentList as $depart)
                    <option value="{{$depart->id}}" {{ old('department') == $depart->id ? "selected" : "" }}>{{$depart->name}}</option>    
                @endforeach
            </select>
            @error('department')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <span id="department_error" class="error_span"></span>
        </div>
    </div>
    <div class="row align-item-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
        <div class="col-md-10">
            <textarea name="description" class="form-control" id="description" placeholder="Enter Description"></textarea>
        </div>
    </div>
    <hr class="my-6">
    <div class="d-flex justify-content-end gap-2">
        <a href="javascript:void(0)" class="btn btn-sm btn-light" id="third_step_previoud"><i class="fa-solid fa-arrow-left"></i>Previous</a>
        <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
        <button type="submit" class="btn btn-sm btn-dark" id="lead_save_button">Save</button>
    </div>
</div>