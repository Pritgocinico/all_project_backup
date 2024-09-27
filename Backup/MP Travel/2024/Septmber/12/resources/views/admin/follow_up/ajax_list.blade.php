<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="follow_up_table">

    <thead>

        <tr>

            <th>#</th>

            <th>Event Name</th>

            <th>Status</th>

            <th>Created By</th>

            <th>Created At</th>

            <th class="text-end">Action</th>

        </tr>

    </thead>

    <tbody>

        @foreach ($followUpList as $key=>$follow)

            <tr>

                <td>{{ $key + 1 }}</td>

                <td><a href="{{ route('follow-up.show', $follow->id) }}">{{ $follow->event_name }}</a></td>

                <td>

                    @php

                        $status = 'warning';

                        $text = 'Pending Lead';

                        if ($follow->event_status == 2) {

                            $status = 'info';

                            $text = 'Assigned Lead';

                        }

                        if ($follow->event_status == 3) {

                            $status = 'secondary';

                            $text = 'Hold Lead';

                        }

                        if ($follow->event_status == 4) {

                            $status = 'success';

                            $text = 'Complete Lead';

                        }

                        if ($follow->event_status == 5) {

                            $status = 'warning';

                            $text = 'Extends Lead';

                        }

                        if ($follow->event_status == 6) {

                            $status = 'danger';

                            $text = 'Cancel Lead';

                        }

                    @endphp

                    <span class="badge bg-{{ $status }}">{{ $text }}</span>

                </td>

                <td>{{ isset($follow->userDetail) ? $follow->userDetail->name : '-' }}</td>

                <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>

                <td class="text-end">

                    @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)

                        <a href="{{ route('follow-up.edit', $follow->id) }}" class="text-dark" data-bs-toggle="tooltip"

                            data-bs-placement="top" title="Edit Follow Up"><i class="fa-solid fa-pen-to-square"></i></a>

                        <a href="javascript:void(0)" class="text-dark" onclick="deleteFollow({{ $follow->id }})"

                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Follow Up">

                            <i class="fa fa-trash-can me-3"></i>

                        </a>

                    @endif

                </td>

            </tr>

        @endforeach

    </tbody>

</table>

