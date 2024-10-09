<div id="third_step_div" style="display: none">
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
        <div class="col-md-4 col-xl-4">
        @if (Auth()->user()->role_id == '1' || Auth()->user()->is_manager == 'yes')
            <select name="assigned_to[]" class="form-control js-example-basic-single" id="assigned_to" multiple>
                <option value="">Select Assignee</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ in_array($user->id, $leadMemberDetail ) ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
            @else
            <select name="assigned_to[]" class="form-control js-example-basic-single read-only" id="assigddned_to">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ $user->id == Auth()->user()->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
            @endif
            @error('assigned_to')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Lead Amount (Optional)</label></div>
        <div class="col-md-4 col-xl-4">
            <input type="number" name="lead_amount" class="form-control" id="lead_amount"
                placeholder="Enter Lead Amount (Optional)" value="{{ $lead->lead_amount }}">
            @error('lead_amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-item-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Department<span class="text-danger">*</span></label></div>
        <div class="col-md-4 col-xl-4">
            <select name="department" class="form-control" id="department">
                <option value="">Select Department</option>
                @foreach ($departmentList as $depart)
                    <option value="{{ $depart->id }}" {{$lead->department == $depart->id ? 'selected' : ''}}>{{ $depart->name }}</option>
                @endforeach
            </select>

            @error('department')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="row align-item-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
        <div class="col-md-10">
            <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{$lead->description}}</textarea>
        </div>
    </div>
    <hr class="my-6">
    <div class="d-flex justify-content-end gap-2">
        <a href="javascript:void(0)" class="btn btn-sm btn-light" id="third_step_previoud"><i
                class="fa-solid fa-arrow-left"></i>Previous</a>
        <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
        <button type="submit" class="btn btn-sm btn-dark" id="lead_save_button">Save</button>
    </div>
</div>
