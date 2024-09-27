@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>{{ $page }}</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                            <a href="javascript:void(0)" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="{{ route('follow-up.create') }}" class="btn btn-sm btn-dark"><i
                                    class="fa-solid fa-plus"></i>
                                New {{ $page }}</a>
                        @endif
                    </div>
                </div>
            </div>


            <div class="px-6 px-lg-7 pt-6">
                <div class="row g-4 mt-6">
                    <div id="sortable" class="list-group ui-sortable">
                        @php $i =0; @endphp
                        @foreach ($leads as $lead)
                            @php $i++; @endphp
                            <li class="list row1 ui-state-default list-unstyled ui-sortable-handle"
                                data-id="{{ $lead->id }}">
                                <div class="d-flex">
                                    <h5>{{ $lead->lead_id }}</h5>

                                </div>
                                <div class="houmanity-card card">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapse{{ $i }}"
                                                    aria-expanded="false" aria-controls="flush-collapseOne">
                                                    Lead Customer Detail
                                                    ({{ isset($lead->customerDetail) ? $lead->customerDetail->name . ' - ' . $lead->customerDetail->customer_id : '-' }})
                                                </button>
                                            </h2>
                                            <div id="flush-collapse{{ $i }}" class="accordion-collapse collapse"
                                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample"
                                                style="">
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

                                                    <div class="row align-items-center g-3 mt-6"
                                                        id="customer_detail_aadhar_div">
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
                                                        <div class="col-md-4 col-xl-4 lead_show_status"
                                                            id="customer_status">
                                                            @php
                                                                $className = 'danger';
                                                                $text = 'Deactive';
                                                                if (
                                                                    isset($lead->customerDetail) &&
                                                                    $lead->customerDetail->status == 1
                                                                ) {
                                                                    $className = 'success';
                                                                    $text = 'Active';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $className }}">{{ $text }}</span>
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
                                        <ul id="task_sortable" class="mb-0 ps-0 ui-sortable"
                                            data-task="{{ $lead->id }}">
                                            @foreach ($lead->followUpDetail as $followUp)
                                                <li class="row1 ui-state-default task_list list-unstyled py-2 ui-sortable-handle"
                                                    data-id="{{ $followUp->id }}" data-task="{{ $lead->id }}">
                                                    <div class="d-flex">
                                                        <div class="me-2">
                                                            <i class="fa-solid fa-ellipsis-v"></i>
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('follow-up.show', $followUp->id) }}">
                                                                {{ $followUp->event_name }}
                                                            </a>
                                                        </div>
                                                        <div class="ms-2">
                                                            <div class="btn-group ml-5">
                                                                @if ($followUp->event_status == 1)
                                                                    <button
                                                                        class="btn btn-light text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        Not Started
                                                                    </button>
                                                                @elseif($followUp->event_status == 4)
                                                                    <button
                                                                        class="btn btn-danger text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        In Progress
                                                                    </button>
                                                                @elseif($followUp->event_status == 5)
                                                                    <button
                                                                        class="btn btn-info text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        On Hold
                                                                    </button>
                                                                @else
                                                                    <button class="btn btn-success text-white btn-sm"
                                                                        type="button">
                                                                        Completed
                                                                    </button>
                                                                @endif
                                                                <ul class="dropdown-menu task-status">
                                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 1) hide @endif"
                                                                            href="javascript:void(0)" data-status="1"
                                                                            data-task="{{ $followUp->id }}">Not Started</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 5) hide @endif"
                                                                            href="javascript:void(0)" data-status="4"
                                                                            data-task="{{ $followUp->id }}">In Progress</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 5) hide @endif"
                                                                            href="javascript:void(0)" data-status="5"
                                                                            data-task="{{ $followUp->id }}">On Hold</a>
                                                                    </li>

                                                                    <li><a class="dropdown-item status @if ($followUp->event_status == 2) hide @endif"
                                                                            href="javascript:void(0)" data-status="2"
                                                                            data-task="{{ $followUp->id }}">Complete</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <div class="ms-2">
                                                            <h6><span class="badge bg-light text-dark">Start: <span
                                                                        class="text-danger">{{ Utility::convertFDYFormat($followUp->event_start) }}</span></span>
                                                            </h6>
                                                            <h6><span class="badge bg-light text-dark">Due: <span
                                                                        class="text-danger">{{ Utility::convertFDYFormat($followUp->event_end) }}</span></span>
                                                            </h6>
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
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        height="30px" width="30px"
                                                                        class="rounded-circle">
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="ms-2">
                                                            <div class="btn-group ml-5">
                                                                @if ($followUp->priority == 2)
                                                                    <button
                                                                        class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        Medium
                                                                    </button>
                                                                @elseif($followUp->priority == 3)
                                                                    <button
                                                                        class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        High
                                                                    </button>
                                                                @elseif($followUp->priority == 4)
                                                                    <button
                                                                        class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        Urgent
                                                                    </button>
                                                                @else
                                                                    <button
                                                                        class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                        type="button" data-bs-toggle="dropdown"
                                                                        aria-expanded="false">
                                                                        Low
                                                                    </button>
                                                                @endif
                                                                <ul class="dropdown-menu priority">
                                                                    <li><a class="dropdown-item status @if ($followUp->priority == 1) hide @endif"
                                                                            href="javascript:void(0)" data-priority="1"
                                                                            data-task="{{ $followUp->id }}">Low</a></li>
                                                                    <li><a class="dropdown-item status @if ($followUp->priority == 2) hide @endif"
                                                                            href="javascript:void(0)" data-priority="2"
                                                                            data-task="{{ $followUp->id }}">Medium</a>
                                                                    </li>
                                                                    <li><a class="dropdown-item status @if ($followUp->priority == 3) hide @endif"
                                                                            href="javascript:void(0)" data-priority="3"
                                                                            data-task="{{ $followUp->id }}">High</a></li>
                                                                    <li><a class="dropdown-item status @if ($followUp->priority == 4) hide @endif"
                                                                            href="javascript:void(0)" data-priority="4"
                                                                            data-task="{{ $followUp->id }}">Urgent</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="text-end ms-auto">
                                                            <div class="d-flex float-end">
                                                                <a href="javascript:void(0);"
                                                                    class="p-1 text-dark SubTask"
                                                                    onclick="openSubTaskModal({{ $followUp->id }})"
                                                                    data-bs-toggle="tooltip" title="Add Follow Task">
                                                                    <i
                                                                        class="fa fa-plus-square follow_up_list_icon me-2"></i>
                                                                </a>
                                                                <a href="{{ route('follow-up.edit', $followUp->id) }}"
                                                                    class="p-1 text-dark" data-bs-toggle="tooltip"
                                                                    title="Add Follow Up">
                                                                    <i
                                                                        class="fa-solid fa-pen-square me-2 follow_up_list_icon"></i></a>
                                                                <a href="javascript:void(0);"
                                                                    class="p-1 text-dark due_date"
                                                                    data-date="{{ $followUp->event_end }}"
                                                                    data-bs-toggle="tooltip" title="Change Due Date"
                                                                    id="changeDueDate" data-id="{{ $followUp->id }}">
                                                                    <i
                                                                        class="fa-solid fa-calendar me-2 follow_up_list_icon"></i>
                                                                </a>
                                                                @if (Auth()->user()->role_id == 1)
                                                                    <a href="javascript:void(0);"
                                                                        data-id="{{ $followUp->id }}"
                                                                        data-bs-toggle="tooltip" title="Delete Follow Up"
                                                                        class="p-1 delete-btn "><i
                                                                            class="fa-solid fa-trash-can me-2 follow_up_list_icon"></i></a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <ul id="subtask-sortable" data-task="{{ $followUp->id }}"
                                                        class="ui-sortable">
                                                        @foreach ($followUp->subTaskData as $sub)
                                                            <li class="row1 sub_task ui-state-default list-unstyled py-1 ui-sortable-handle"
                                                                data-id="{{ $sub->id }}">
                                                                <div class="d-flex">
                                                                    <div class="me-2">
                                                                        <i class="fa-solid fa-ellipsis-v"></i>
                                                                    </div>
                                                                    <div class="mr-25">
                                                                        <div class="round">
                                                                            <input type="checkbox"
                                                                                id="checkbox-{{ $sub->id }}"
                                                                                class="chk"
                                                                                data-id="{{ $sub->id }}"
                                                                                name="chk-item"
                                                                                {{ $sub->task_status == 1 ? 'checked' : '' }}>
                                                                            <label
                                                                                for="checkbox-{{ $sub->id }}"></label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ms-2">
                                                                        @if ($sub->task_status == 1)
                                                                            <del>{{ $sub->note }}</del> <span
                                                                                class="badge bg-light text-dark">completed
                                                                                {{ date('d F, Y', strtotime($sub->complete_date)) }}</span>
                                                                        @else
                                                                            {{ $sub->note }} Created
                                                                            {{ Utility::convertFDYFormat($sub->created_at) }}
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-end ms-auto">
                                                                        <div class="d-flex float-end">
                                                                            <a href="javascript:void(0);"
                                                                                class="p-1 text-dark editsubtask"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Edit Sub Follow Up Task"
                                                                                data-id="{{ $sub->id }}"
                                                                                data-note = "{{ $sub->note }}"><i
                                                                                    class="fa-solid fa-pen-square me-2 follow_up_list_icon"></i></a>
                                                                            </a>
                                                                            @if (Auth()->user()->role_id == 1)
                                                                            <a href="javascript:void(0);"
                                                                                data-id="{{ $sub->id }}"
                                                                                data-bs-toggle="tooltip"
                                                                                title="Delete Sub Follow Up Task"
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
                                            href="#collapseExample{{ $i }}" data-val="{{ $i }}"
                                            role="button" aria-expanded="false" aria-controls="collapseExample">
                                            <span class="lh-lg"> <i class="fa-solid fa-plus"></i>
                                                Add Follow Up</span>
                                        </a>
                                        <div class="mt-2 collapse" id="collapseExample{{ $i }}"
                                            style="">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <form action="{{ route('add_follow_up_data') }}"
                                                                method="POST" class="row g-3"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="col-12">
                                                                    <label for="Subject" class="form-label">Subject <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        name="subject" placeholder="Enter Subject"
                                                                        id="Subject" value="" placeholder=" ">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="StartDate" class="form-label">Start Date
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="date" name="start_date"
                                                                        class="form-control start_date" id="start_date"
                                                                        data-id="{{ $i }}"
                                                                        value="{{ date('Y-m-d') }}"
                                                                        min="{{ date('Y-m-d') }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="EndDate" class="form-label">Due
                                                                        Date</label>
                                                                    <input type="date" name="due_date"
                                                                        class="form-control"
                                                                        id="due_date_{{ $i }}"
                                                                        value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                                        min="{{ date('Y-m-d') }}">
                                                                </div>
                                                                <input type="hidden" name="lead_id" id="lead_id"
                                                                    value="{{ isset($followUp->leadDetail) ? $followUp->leadDetail->id : '' }}">
                                                                <div class="col-md-6">
                                                                    <label for="Assignees"
                                                                        class="form-label">Assignees</label>
                                                                    <select id="Assignees" placeholder="Select..."
                                                                        class="form-control select2<?php echo $i; ?>"
                                                                        name="assignees[]" multiple="multiple">
                                                                        @if (count($employees) > 0)
                                                                            @foreach ($employees as $employee)
                                                                                <option value="{{ $employee->id }}">
                                                                                    {{ $employee->name }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @if ($errors->has('assignees'))
                                                                        <span
                                                                            class="text-danger">{{ $errors->first('assignees') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="Priority" class="form-label">Priority
                                                                    </label>
                                                                    <select id="Priority" class="form-select"
                                                                        name="priority">
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
                                                                        <div
                                                                            class="input-group hdtuto control-group lst increment">
                                                                            <input type="file" name="filenames[]"
                                                                                class="myfrm form-control">
                                                                            <div class="input-group-btn">
                                                                                <button class="btn btn-success ms-2"
                                                                                    type="button"><i
                                                                                        class="fa-solid fa-plus"></i>Add</button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="clone d-none">
                                                                            <div class="hdtuto control-group lst input-group"
                                                                                style="margin-top:10px">
                                                                                <input type="file" name="filenames[]"
                                                                                    class="myfrm form-control">
                                                                                <div class="input-group-btn">
                                                                                    <button class="btn btn-danger ms-2"
                                                                                        type="button"><i
                                                                                            class="fa-solid fa-trash-can glyphicon-remove"></i>
                                                                                        Remove</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 p-3">
                                                                    <button type="submit"
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
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
        {{-- export modal --}}
        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Followup</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="" class="form" action="#">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="format" class="form-control" id="export_format">
                                            <option value="">Select Format</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                        <span id="format_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="exportCSV()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Sub Task Modal -->
    <div class="modal fade" id="SubTaskListModal" tabindex="-1" aria-labelledby="SubTaskListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SubTaskListModalLabel">Add Follow Up Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('followup_checklist_item') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="Title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="note" id=""
                                value="{{ old('title') }}" placeholder="Enter Title" required>
                            @if ($errors->has('note'))
                                <span class="text-danger">{{ $errors->first('note') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="follow_up_id" class="subtask-id" value="">
                        <input type="hidden" name="status" value="0">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dueDateModal" tabindex="-1" aria-labelledby="dueDateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dueDateModalLabel">Edit Due Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update_due_date') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="dueDate" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control duedate" name="due_date" id="inputDate">
                            @if ($errors->has('val'))
                                <span class="text-danger">{{ $errors->first('val') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" class="taskId" id="due_date_model_id" value="">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSubTaskListModal" tabindex="-1" aria-labelledby="editSubTaskListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubTaskListModalLabel">Edit Sub Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update_checklist_note') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="Title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control sub-val" name="val" id="edit_note"
                                value="{{ old('title') }}" placeholder="Enter Title" required>
                            @if ($errors->has('val'))
                                <span class="text-danger">{{ $errors->first('val') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" class="sub-id" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var number = "{{ $i }}";
        $(document).ready(function(e) {
            followupAjax(1);
            $('[data-bs-toggle="tooltip"]').tooltip();
        })
        $('.select-id').on('click', function(e) {
            var id = $(this).data('val');
            $(`.select2${id}`).select2()
        })

        function openSubTaskModal(id) {
            $('.subtask-id').val(id);
            $('#SubTaskListModal').modal('show');
        }
        $('.editsubtask').on('click', function(e) {
            var id = $(this).data('id');
            var note = $(this).data('note');
            $('.sub-id').val(id);
            $('#edit_note').val(note);
            $('#editSubTaskListModal').modal('show');
        })
        $('#changeDueDate').on('click', function(e) {
            var id = $(this).data('id');
            var date = $(this).data('date');
            $('#due_date_model_id').val(id);
            $('#inputDate').val(date);
            $('#dueDateModal').modal('show');
        })

        function followupAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('followup-ajax-list') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#followup_table_ajax').html('');
                    $('#followup_table_ajax').html(res);
                    $("#follow_up_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#follow_up_table_filter input');
                            $searchInput.attr('id', 'follow_up_search'); // Assign the ID
                            $searchInput.attr('placeholder', 'Search Follow Up');
                        },
                        lengthChange: false,
                        "order": [
                            [0, 'asc']
                        ],
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 0
                        }]
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            followupAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('followup-export') }}";
            var format = $('#export_format').val();
            var search = $('#follow_up_search').val();
            $('#format_error').html('');
            if (format.trim() == "") {
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv', 'excel', 'pdf'];
            if (!allowValues.includes(format)) {
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }
        var calendar = $('#follow_up_calendar').fullCalendar({
            timeZone: 'UTC',
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: "{{ route('followup-ajax') }}",
            displayEventTime: false,
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function(event_start, event_end, allDay) {
                console.log(event_start);
                var start = $.fullCalendar.formatDate(event_start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(event_end, "Y-MM-DD");
            },
            eventClick: function(event) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('follow-up.destroy', '') }}" + "/" + event.id,
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
                                        followupAjax(1);
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
        });

        function deleteFollow(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('follow-up.destroy', '') }}" + "/" + id,
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    followupAjax(1);
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
        $('.start_date').on('change', function(e) {
            var id = $(this).data('id');
            var date = $(this).val();
            var next_date = moment(date).add(1, 'days').format('YYYY-MM-DD');
            $(`#due_date_${id}`).val(next_date);
            $(`#due_date_${id}`).attr('min', date);
        });
        $('.chk').change(function() {
            var val = 0;
            if (this.checked) {
                val = 1
            } else {
                val = 0;
            }
            var id = $(this).data('id');
            $('#loader').removeClass('hidden');
            $.ajax({
                url: "{{ route('update_checklist_status') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'val': val
                },
                dataType: 'json',
                success: function(data) {
                    $('#loader').addClass('hidden');
                    location.reload(true);
                }
            })
        });
        $(document).on('click', '.delete-btn', function() {
            var task_id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('follow-up.destroy', '') }}" + "/" + task_id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    top.location.href =
                                        "{{ route('follow-up.index') }}";
                                }
                            });
                        }
                    });
                }
            });
        });
        $(document).on('click', '.remove-item', function() {
            var item_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.checklist_item', '') }}" + "/" + item_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(true);
                                }
                            });
                        }
                    });
                }
            });
        });
        $('.task-status li a').on('click', function() {
            var status = $(this).data('status');
            var task_id = $(this).data('task');
            $('#loader').removeClass('hidden');
            $.ajax({
                url: "{{ route('change_task_status') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status': status,
                    'id': task_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#loader').addClass('hidden');
                    Swal.fire({
                        title: 'Status Changed!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            top.location.href = '{{ route('follow-up.index') }}';
                        }
                    });
                }
            });
        });
        $('.priority li a').on('click', function() {
            var priority = $(this).attr('data-priority');
            var task_id = $(this).attr('data-task');
            $('#loader').removeClass('hidden');
            $.ajax({
                url: "{{ route('change_priority') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'priority': priority,
                    'id': task_id
                },
                dataType: 'json',
                success: function(data) {
                    $('#loader').addClass('hidden');
                    Swal.fire({
                        title: 'Priority Changed!',
                        text: "Priority Changed Successfully.",
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(true);
                        }
                    });
                }
            });
        });
    </script>
@endsection
