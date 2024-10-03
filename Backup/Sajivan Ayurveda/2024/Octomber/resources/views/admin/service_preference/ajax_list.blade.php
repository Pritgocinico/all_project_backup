<table class="table table-hover table-sm table-nowrap table-scrolling table-scrolling table-responsive mt-6 border" id="service_preference_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($servicePreferenceList as $key=>$servicePreference)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>
                    {{$servicePreference->name}}
                </td>
                <td>{{ isset($servicePreference->userDetail) ? $servicePreference->userDetail->name : '-' }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($servicePreference->created_at) }}</td>
                <td class="text-end">
                <div class="icon-td">
                
                    @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
                        <a href="javascript:void(0)" onclick="editServicePreference({{ $servicePreference->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Edit Service Preference"><i class="fa-solid fa-pen-to-square"></i></a>

                        <a href="javascript:void(0)" onclick="deleteServicePreference({{ $servicePreference->id }})"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Service Preference"><i
                                class="fa fa-trash-can"></i></a>
                    @endif
</div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
