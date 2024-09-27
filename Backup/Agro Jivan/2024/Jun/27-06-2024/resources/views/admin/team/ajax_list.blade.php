<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Team Id</th>
            <th class="min-w-125px">Team Name</th>
            <th class="min-w-125px">Manager Name</th>
            <th class="min-w-125px">Team Size</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($teamList as $key=>$team)
            <tr>
            <td>{{ $teamList->firstItem() + $key}}</td>
                <td>
                    @php $route = route('sale-manager-team.show',$team->id) @endphp
                    @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                        @php $route = route('team.show',$team->id) @endphp
                    @endif
                    <a href="{{$route}}" class="pre-agro-emp">{{$team->team_id}}</a>
                </td>
                <td>{{$tean->team_name ?? "-"}}</td>
                <td>{{ isset($team->managerDetail) ? $team->managerDetail->name : '-' }}</td>
                <td>{{ isset($team->teamMember) ? count($team->teamMember) : '0' }}</td>
                <td>{{ Utility::convertDmyWith12HourFormat($team->created_at) }}</td>
                <td>
                    @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                    <a class="btn btn-icon btn-info w-30px h-30px me-3" href="#}" onclick="editManager({{$team->id}})" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit Manager" data-bs-original-title="Edit Manager" aria-describedby="tooltip553274">
                        <i class="fa-solid fa-edit"></i>
                    </a>
                    @endif
                    
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3" href="#" onclick="addMember({{ $team->id }})" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Add New Member" data-bs-original-title="Add New Member">
                        <i class="fas fa-user-plus"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $teamList->links('pagination::bootstrap-5') }}
</div>
