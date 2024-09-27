<div class="row gy-5 g-xl-10 mt-3 agro-parent-main-dashboard">

    @forelse ($teamList as $team)
        <div class="col-md-4 gy-5 g-xl-10 mt-3">
            <div class="card h-lg-100">
                <div class="card-body d-flex flex-column">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-center mt-3">{{ $team->team_id }}</h4>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('team.show', $team->id) }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th>Rank</th>
                                    <th class="min-w-50px">User Name</th>
                                    <th class="min-w-50px">Pending</th>
                                    <th class="min-w-50px">Total</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($team->teamDetails as $key => $user)
                                    @if ($key <= 4)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a href="{{ route('employees.show', isset($user->userDetail) ? $user->userDetail->id : '2') }}"
                                                    class="pre-agro-emp">
                                                    {{ isset($user->userDetail) ? $user->userDetail->name : '-' }}</a>
                                            </td>
                                            <td>
                                                {{ $user->total_pending_orders_count }}
                                            </td>
                                            <td>
                                                {{ $user->total_confirm_orders_count }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">Team Not Found.</div>
    @endforelse
</div>
