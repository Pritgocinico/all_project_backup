@php $i =0; @endphp
@forelse ($leads as $lead)
    @php $i++; @endphp
    <li class="list row1 ui-state-default list-unstyled ui-sortable-handle" data-id="{{ $lead->id }}">
        <div class="houmanity-card card">
            <div class="accordion" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapse{{ $i }}" aria-expanded="false"
                            aria-controls="flush-collapseOne">
                            <h4>Lead Customer Detail
                                ({{ isset($lead->customerDetail) ? $lead->customerDetail->name . ' - ' . $lead->customerDetail->customer_id : '-' }})
                                {{ $lead->lead_id }}</h4>
                    </h2>
                    </button>
                    </h2>
                    <div id="flush-collapse{{ $i }}" class="accordion-collapse collapse show"
                        aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample" style="">
                        <div class="accordion-body">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label>
                                </div>
                                <div class="col-md-4 col-xl-4" id="customer_name">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Customer
                                        Id</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_id_div">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->customer_id : '-' }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Email</label>
                                </div>
                                <div class="col-md-4 col-xl-4" id="customer_email">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->email : '-' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Phone
                                        Number</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_mobile">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->mobile_number : '-' }}
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6" id="customer_detail_aadhar_div">
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar
                                        Card</label></div>
                                <div class="col-md-4 col-xl-4" id="aadhar_card_number">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->aadhar_number : '-' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card
                                        File</label></div>
                                <div class="col-md-4 col-xl-4" id="cust_aadhar_card_file">
                                    @if (isset($lead->customerDetail) && $lead->customerDetail->aadhar_card_file !== null)
                                        <a href="{{ asset('storage/' . $lead->customerDetail->aadhar_card_file) }}"
                                            class="btn btn-dark" target="_blank">view</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Pan
                                        Card</label></div>
                                <div class="col-md-4 col-xl-4" id="pan_card_number">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->pan_card_number : '-' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Pan Card
                                        File</label></div>
                                <div class="col-md-4 col-xl-4" id="cust_pan_card_file">
                                    @if (isset($lead->customerDetail) && $lead->customerDetail->pan_card_file !== null)
                                        <a href="{{ asset('storage/' . $lead->customerDetail->pan_card_file) }}"
                                            class="btn btn-dark" target="_blank">view</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Address</label>
                                </div>
                                <div class="col-md-4 col-xl-4" id="customer_address">
                                    @if (isset($lead->customerDetail))
                                        @php $customer = $lead->customerDetail; @endphp
                                        {{ $customer->address }},<br />
                                        {{ isset($customer->cityDetail) ? $customer->cityDetail->name : '-' }},
                                        {{ isset($customer->stateDetail) ? $customer->stateDetail->name : '-' }},<br />
                                        {{ isset($customer->countryDetail) ? $customer->countryDetail->name : '-' }},{{ $customer->pin_code }}
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Status</label>
                                </div>
                                <div class="col-md-4 col-xl-4 lead_show_status" id="customer_status">
                                    @php
                                        $className = 'danger';
                                        $text = 'Deactive';
                                        if (isset($lead->customerDetail) && $lead->customerDetail->status == 1) {
                                            $className = 'success';
                                            $text = 'Active';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $className }}">{{ $text }}</span>
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Customer
                                        Reference</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_reference">
                                    {{ isset($lead->customerDetail) ? $lead->customerDetail->reference : '-' }}
                                </div>
                                <div class="col-md-2 "><label class="form-label mb-0">GST
                                        Certificate</label></div>
                                <div class="col-md-4" id="gst_certificate_file">
                                    @if (isset($lead->customerDetail) && $lead->customerDetail->gst_certificate !== null)
                                        <a href="{{ asset('storage/' . $lead->customerDetail->gst_certificate) }}"
                                            class="btn btn-dark" target="_blank">view</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul id="task_sortable" class="mb-0 ps-0 ui-sortable" data-task="{{ $lead->id }}">
                    @foreach ($lead->followUpDetail as $followUp)
                        <li class="row1 ui-state-default task_list list-unstyled py-2 ui-sortable-handle"
                            data-id="{{ $followUp->id }}" data-task="{{ $lead->id }}">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="fa-solid fa-ellipsis-v"></i>
                                </div>
                                <div class="ms-2">
                                    @if (count($followUp->followUpMemberDetail) > 0)
                                        @foreach ($followUp->followUpMemberDetail as $assignee)
                                            @php $image = asset('assets/img/follow_up/profile.png') @endphp
                                            @if (isset($assignee->userDetail) && $assignee->userDetail->profile_image)
                                                @php $image = asset('storage/'.$assignee->userDetail->profile_image) @endphp
                                            @endif
                                            <img src="{{ $image }}"
                                                title="{{ isset($assignee->userDetail) ? $assignee->userDetail->name : '-' }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top" height="30px"
                                                width="30px" class="rounded-circle">
                                        @endforeach
                                    @endif
                                </div>
                                <div class="ms-2">
                                    <a href="{{ route('follow-up.show', $followUp->id) }}">
                                        {{ $followUp->follow_up_code }}
                                        {{ $followUp->event_name }}
                                    </a>
                                </div>
                                <div class="ms-2">
                                    <h6><span class="badge bg-light text-dark">Start: <span
                                                class="text-danger">{{ Utility::convertFDYFormat($followUp->event_start) }}</span>
                                            \ Due: <span
                                                class="text-danger">{{ Utility::convertFDYFormat($followUp->event_end) }}</span>
                                    </h6>
                                </div>

                                <div class="text-end ms-auto">
                                    <div class="d-flex float-end">
                                        <a href="javascript:void(0);" class="p-1 text-dark SubTask"
                                            onclick="openSubTaskModal({{ $followUp->id }},'{{ $followUp->follow_up_code }}')"
                                            data-bs-toggle="tooltip" title="Add Follow Task">
                                            <i class="fa fa-plus-square follow_up_list_icon me-2"></i>
                                        </a>
                                        <a href="{{ route('follow-up.edit', $followUp->id) }}" class="p-1 text-dark"
                                            data-bs-toggle="tooltip" title="Edit Follow Up">
                                            <i class="fa-solid fa-pen-square me-2 follow_up_list_icon"></i></a>
                                        <a href="javascript:void(0);" class="p-1 text-dark due_date"
                                            data-date="{{ $followUp->event_end }}" data-bs-toggle="tooltip"
                                            title="Change Due Date" id="changeDueDate" onclick="changeDueDate({{ $followUp->id}} , '{{ $followUp->event_end }}')"
                                            data-id="{{ $followUp->id }}">
                                            <i class="fa-solid fa-calendar me-2 follow_up_list_icon"></i>
                                        </a>
                                        @if (Auth()->user()->role_id == 1)
                                            <a href="javascript:void(0);" data-id="{{ $followUp->id }}"
                                                data-bs-toggle="tooltip" title="Delete Follow Up"
                                                class="p-1 delete-btn "><i
                                                    class="fa-solid fa-trash-can me-2 follow_up_list_icon"></i></a>
                                        @endif
                                        <div class="btn-group ml-5">
                                            @if ($followUp->event_status == 1)
                                                <button class="btn btn-light text-white btn-sm dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    In Progress
                                                </button>
                                            @elseif($followUp->event_status == 5)
                                                <button class="btn btn-info text-white btn-sm dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    On Hold
                                                </button>
                                            @else
                                                <button class="btn btn-success text-white btn-sm" type="button">
                                                    Completed
                                                </button>
                                            @endif
                                            <ul class="dropdown-menu task-status">
                                                <li><a class="dropdown-item status @if ($followUp->event_status == 1) hide @endif"
                                                        href="javascript:void(0)" data-status="1" onclick="changeStatus(1,{{$followUp->id}})"
                                                        data-task="{{ $followUp->id }}">In
                                                        Progress</a>
                                                </li>
                                                <li><a class="dropdown-item status @if ($followUp->event_status == 5) hide @endif"
                                                        href="javascript:void(0)" data-status="5" onclick="changeStatus(5,{{$followUp->id}})"
                                                        data-task="{{ $followUp->id }}">On
                                                        Hold</a>
                                                </li>

                                                <li><a class="dropdown-item status @if ($followUp->event_status == 2) hide @endif"
                                                        href="javascript:void(0)" data-status="2" onclick="changeStatus(2,{{$followUp->id}})"
                                                        data-task="{{ $followUp->id }}">Completed</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="ms-2">
                                            <div class="btn-group ml-5">
                                                @if ($followUp->priority == 2)
                                                    <button class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Medium
                                                    </button>
                                                @elseif($followUp->priority == 3)
                                                    <button class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        High
                                                    </button>
                                                @elseif($followUp->priority == 4)
                                                    <button class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Urgent
                                                    </button>
                                                @else
                                                    <button class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Low
                                                    </button>
                                                @endif
                                                <ul class="dropdown-menu priority">
                                                    <li><a class="dropdown-item status @if ($followUp->priority == 1) hide @endif"
                                                            href="javascript:void(0)" data-priority="1" onclick="changePriority(1,{{$followUp->id}})"
                                                            data-task="{{ $followUp->id }}">Low</a>
                                                    </li>
                                                    <li><a class="dropdown-item status @if ($followUp->priority == 2) hide @endif"
                                                            href="javascript:void(0)" data-priority="2" onclick="changePriority(2,{{$followUp->id}})"
                                                            data-task="{{ $followUp->id }}">Medium</a>
                                                    </li>
                                                    <li><a class="dropdown-item status @if ($followUp->priority == 3) hide @endif"
                                                            href="javascript:void(0)" data-priority="3" onclick="changePriority(3,{{$followUp->id}})"
                                                            data-task="{{ $followUp->id }}">High</a>
                                                    </li>
                                                    <li><a class="dropdown-item status @if ($followUp->priority == 4) hide @endif"
                                                            href="javascript:void(0)" data-priority="4" onclick="changePriority(4,{{$followUp->id}})"
                                                            data-task="{{ $followUp->id }}">Urgent</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul id="subtask-sortable" data-task="{{ $followUp->id }}" class="ui-sortable">
                                @foreach ($followUp->subTaskData as $sub)
                                    <li class="row1 sub_task ui-state-default list-unstyled py-1 ui-sortable-handle"
                                        data-id="{{ $sub->id }}">
                                        <div class="d-flex">
                                            <div class="me-2">
                                                <i class="fa-solid fa-ellipsis-v"></i>
                                            </div>
                                            <div class="mr-25">
                                                <div class="round">
                                                    <input type="checkbox" id="checkbox-{{ $sub->id }}" onchange="changeSubTaskStatue({{$sub->id}})"
                                                        class="chk" data-id="{{ $sub->id }}" name="chk-item"
                                                        {{ $sub->task_status == 1 ? 'checked' : '' }}>
                                                    <label for="checkbox-{{ $sub->id }}"></label>
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                @if ($sub->task_status == 1)
                                                    <del>{{ $sub->note }}</del> \
                                                    <span class="badge bg-light text-dark">Completed :
                                                        {{ date('d F, Y', strtotime($sub->complete_date)) }}</span>- <b>{{ $sub->sub_follow_up_code }}</b>
                                                @else
                                                    {{ $sub->note }} \  Created :
                                                    {{ Utility::convertFDYFormat($sub->created_at) }} - <b>{{ $sub->sub_follow_up_code }} </b>
                                                @endif
                                            </div>
                                            <div class="text-end ms-auto">
                                                <div class="d-flex float-end">
                                                    <a href="javascript:void(0);" class="p-1 text-dark editsubtask" 
                                                
                                                        onclick="openEditSubTaskModal({{$sub->id}},'{{$sub->sub_follow_up_code}}','{{$sub->note}}')"
                                                        data-bs-toggle="tooltip" title="Edit Sub Follow Up Task"
                                                        data-id="{{ $sub->id }}"
                                                        data-code="{{ $sub->sub_follow_up_code }}"
                                                        data-note = "{{ $sub->note }}"><i
                                                            class="fa-solid fa-pen-square me-2 follow_up_list_icon"></i></a>
                                                    </a>
                                                    @if (Auth()->user()->role_id == 1)
                                                        <a href="javascript:void(0);" data-id="{{ $sub->id }}"
                                                            data-bs-toggle="tooltip" title="Delete Sub Follow Up Task"
                                                            class="p-1 remove-item"><i
                                                                class="fa-solid fa-trash-can me-2 follow_up_list_icon"></i></a></a>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>

                <a class="mt-2 select-id collapsed" data-bs-toggle="collapse"
                    href="#collapseExample{{ $i }}" data-val="{{ $i }}" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    <span class="lh-lg"> <i class="fa-solid fa-plus"></i>
                        Add Follow Up</span>
                </a>
                <div class="mt-2 collapse" id="collapseExample{{ $i }}" style="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('add_follow_up_data') }}" id="add_follow_up_data"
                                        method="POST" class="row g-3" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-12">
                                            <label for="Subject" class="form-label">Subject <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="subject"
                                                placeholder="Enter Subject" id="Subject" value=""
                                                placeholder="" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="StartDate" class="form-label">Start Date
                                                <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" class="form-control start_date"
                                                id="start_date" required data-id="{{ $i }}"
                                                value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="EndDate" class="form-label">Due
                                                Date</label>
                                            <input type="date" name="due_date" required class="form-control"
                                                id="due_date_{{ $i }}"
                                                value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                min="{{ date('Y-m-d') }}">
                                        </div>
                                        <input type="hidden" name="lead_id" id="lead_id"
                                            value="{{ isset($followUp->leadDetail) ? $followUp->leadDetail->id : '' }}"
                                            required>
                                        <div class="col-md-6">
                                            <label for="Assignees" class="form-label">Assignees</label>
                                            <select id="Assignees" placeholder="Select..."
                                                class="form-control select2<?php echo $i; ?>" name="assignees[]"
                                                multiple="multiple" required>
                                                @if (count($employees) > 0)
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('assignees'))
                                                <span class="text-danger">{{ $errors->first('assignees') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Priority" class="form-label">Priority
                                            </label>
                                            <select id="Priority" class="form-select" required name="priority">
                                                <option value="1">Low</option>
                                                <option value="2" selected="">Medium
                                                </option>
                                                <option value="3">High</option>
                                                <option value="4">Urgent</option>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label">Description</label>
                                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="5"
                                                placeholder="Enter Description"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="" class="form-label">Upload
                                                Files</label>
                                            <div class="col-md-6">
                                                <div class="input-group hdtuto control-group lst increment">
                                                    <input type="file" name="filenames[]"
                                                        class="myfrm form-control">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-success ms-2" type="button"><i
                                                                class="fa-solid fa-plus"></i>Add</button>
                                                    </div>
                                                </div>
                                                <div class="clone d-none">
                                                    <div class="hdtuto control-group lst input-group"
                                                        style="margin-top:10px">
                                                        <input type="file" name="filenames[]" required
                                                            class="myfrm form-control">
                                                        <div class="input-group-btn">
                                                            <button class="btn btn-danger ms-2" type="button"><i
                                                                    class="fa-solid fa-trash-can glyphicon-remove"></i>
                                                                Remove</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 p-3">
                                            <button type="submit" id="add_follow_up_button"
                                                class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </li>
    <hr class="my-6" />
@empty
    <h5 class="text-center">No Follow Up Data Found.</h5>
@endforelse
