<table class="table table-hover table-sm table-nowraps mt-6 border user_follow_up_table">

    <thead>

        <tr>

            <th>#</th>
            <th>Follow Up Subject</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Member Name</th>
            <th>Follow up Status</th>
            <th>Follow up Priority</th>
            <th>Created At</th>
            <th class="text-end">Action</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($followUpData as $key=>$lead)
            <tr>

                <td>{{ $key + 1 }}</td>

                <td>{{$lead->event_name}}
                </td>

                <td>{{$lead->event_start}}</td>
                <td>{{$lead->event_end}}</td>

                <td>

                    @foreach ($lead->followUpMemberDetail as $member)

                        @if (isset($member->userDetail))

                            {{ $member->userDetail->name }}@if (!$loop->last),@endif

                        @else

                            -

                        @endif

                    @endforeach

                </td>

                <td>

                    @php

                        $status = 'warning';

                        $text = 'Pending Lead';

                        if ($lead->status == 2) {

                            $status = 'info';

                            $text = 'Assigned Lead';

                        }

                        if ($lead->status == 3) {

                            $status = 'secondary';

                            $text = 'Hold Lead';

                        }

                        if ($lead->status == 4) {

                            $status = 'success';

                            $text = 'Completed Lead';

                        }

                        if ($lead->status == 5) {

                            $status = 'warning';

                            $text = 'Extends Lead';

                        }

                        if ($lead->status == 6) {

                            $status = 'danger';

                            $text = 'Cancel Lead';

                        }

                    @endphp

                    <span class="badge bg-{{ $status }}" style="font-size: 16px !important">{{ $text }}</span>

                </td>

                <td >
                    @if ($lead->priority == 1)
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-light text-dark fs-14" style="font-size: 16px !important">Low</span>
                    </div>
                @elseif($lead->priority == 2)
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-info fs-14">Medium</span>
                    </div>
                @elseif($lead->priority == 3)
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-secondary fs-14">High </span>
                    </div>
                @elseif($lead->priority == 4)
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-danger fs-14">Urgent</span>
                    </div>
                @endif</td>

                <td>{{ Utility::convertDmyAMPMFormat($lead->created_at) }}</td>

                <td class="text-end">

                    @if (collect($accesses)->where('menu_id', '18')->first()->status == 2)

                    <a href="{{ route('follow-up.edit', $lead->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit lead"><i class="fa-solid fa-pen-to-square"></i></a>
                    @if(Auth()->user()->role_id == 1)
                    <a href="javascript:void(0)" onclick="deletelead({{$lead->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete lead"><i class="fa-solid fa-trash-can"></i></a>
                    @endif
                    @endif

                </td>

            </tr>

        @endforeach

    </tbody>

</table>