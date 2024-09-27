<table class="table table-hover table-sm table-nowraps mt-6 border" id="lead_table">

    <thead>

        <tr>

            <th>#</th>

            <th>Lead</th>

            <th>Customer Name</th>

            <th>Lead Amount</th>

            <th>Lead Assigned</th>

            <th>Status</th>

            <th>Created By</th>

            <th>Created At</th>

            <th class="text-end">Action</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($leads as $key=>$lead)

            <tr>

                <td>{{ $key + 1 }}</td>

                <td><a href="{{ route('leads.show', $lead->id) }}">{{ $lead->lead_id }}</a></td>

                <td>{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}</td>

                <td>&#x20B9; {{ number_format($lead->lead_amount ?? 0,2) }}</td>

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

                            $text = 'Complete Lead';

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