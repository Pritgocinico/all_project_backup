<div class="row gy-5 g-xl-10 mt-3 agro-parent-main-dashboard">

    @forelse ($teamList as $team)
        <div class="col-md-6 gy-5 g-xl-10 mt-3">
            <div class="card h-lg-100">
                <div class="card-body d-flex flex-column">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-center mt-3">{{ $team->team_id }}</h4>
                            <a href="#" class="btn btn-primary btn-sm">Total Orders:-
                                {{ $team->total_orders_count }}</a>
                        </div>
                        <div class="col-md-4">
                            <p>
                                <a href="#" class="btn btn-primary"
                                    onclick="teamViewAll({{ $team->id }})">View
                                    All</a>
                            </p>
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
                                @php $number = 1; @endphp
                                @foreach ($team->teamDetails as $key => $user)
                                    <tr>
                                        <td>{{ $number++ }}</td>
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
