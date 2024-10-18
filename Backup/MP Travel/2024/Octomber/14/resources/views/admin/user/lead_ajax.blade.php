<table class="table table-hover table-sm table-nowraps mt-6 border" id="lead_table">

    <thead>

        <tr>

            <th>#</th>

            <th>Lead</th>

            <th>Customer Name</th>

            <th>Lead Assigned</th>
            
            <th>Status</th>

            <th>Created By</th>

            <th>Created At</th>

            <th class="text-end">Action</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($leadData as $key=>$lead)
            <tr>

                <td>{{ $key + 1 }}</td>

                <td>
                    <a href="{{ route('leads.show', $lead->id) }}">Lead - {{ $lead->id }} /
                        {{ucfirst(str_replace ('_', ' ', $lead->invest_type))}} 
                        @if($lead->invest_type == "investments" || $lead->invest_type == 'general insurance')
                        / {{ucfirst(str_replace ('_', ' ', $lead->insurance_type))}} 
                        @elseif($lead->invest_type == 'travel' && isset($lead->travelLeadData))
                        / {{ucfirst(str_replace ('_', ' ', $lead->travelLeadData->travel_inquiry_type))}}
                        @endif
                    </a>
                </td>

                <td>{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}</td>

                <td>

                    @foreach ($lead->leadMemberDetail as $member)

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

                        if ($lead->lead_status == 2) {

                            $status = 'info';

                            $text = 'Assigned Lead';

                        }

                        if ($lead->lead_status == 3) {

                            $status = 'secondary';

                            $text = 'Hold Lead';

                        }

                        if ($lead->lead_status == 4) {

                            $status = 'success';

                            $text = 'Completed Lead';

                        }

                        if ($lead->lead_status == 5) {

                            $status = 'warning';

                            $text = 'Extends Lead';

                        }

                        if ($lead->lead_status == 6) {

                            $status = 'danger';

                            $text = 'Cancel Lead';

                        }

                    @endphp

                    <span class="badge bg-{{ $status }}">{{ $text }}</span>

                </td>

                <td>{{ isset($lead->userDetail) ? $lead->userDetail->name :"-" }}</td>

                <td>{{ Utility::convertDmyAMPMFormat($lead->created_at) }}</td>

                <td class="text-end">

                    @if (collect($accesses)->where('menu_id', '18')->first()->status == 2)

                    <a href="{{ route('leads.edit', $lead->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit lead"><i class="fa-solid fa-pen-to-square"></i></a>
                    @if(Auth()->user()->role_id == 1)
                    <a href="javascript:void(0)" onclick="deletelead({{$lead->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete lead"><i class="fa-solid fa-trash-can"></i></a>
                    @endif
                    @endif

                </td>

            </tr>

        @endforeach

    </tbody>

</table>