<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-black fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="w-75px">Employee Name</th>
            <th class="w-15px">Total <br> Order</th>
            <th class="w-15px">Pending <br>Order</th>
            <th class="w-15px">Confirmed <br> Order</th>
            <th class="w-15px">on Delivery <br> Order</th>
            <th class="w-15px">Delivered <br>Order</th>
            <th class="w-15px">Cancel <br> Order</th>
            <th class="w-15px">Return <br> Order</th>
            <th class="w-15px">Amount</th>
            <th class="w-15px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="w-100px">Actions</th>
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        <tr>
            <td>1</td>
            <td>
                @php $route = route('sale-employee-view',isset($team->managerDetail)?$team->managerDetail->id:1) @endphp
                @if (Auth()->user() !== null && Auth()->user()->role_id == 1)
                    @php $route = route('employees.show',isset($team->managerDetail)?$team->managerDetail->id:1) @endphp
                @endif
                <a href="{{ $route }}" class="pre-agro-emp">
                    {{ isset($team->managerDetail) ? $team->managerDetail->name : '-' }}</a>
                </td>
                <td>{{ $team->all_order_count }}</td>
            <td>{{ $team->pending_order_count }}</td>
            <td>{{ $team->on_delivery_order_count }}</td>
            <td>{{ $team->confirm_order_count }}</td>
            <td>{{ $team->delivered_order_count }}</td>
            <td>{{ $team->cancel_order_count }}</td>
            <td>{{ $team->return_order_count }}</td>
            <td>
                @php $amount = 0; @endphp
                @foreach ($team->deliveredOrder as $deliver)
                    @php $amount += $deliver->amount; @endphp
                @endforeach
                {{ $amount }}
            </td>
            <td>
                @php
                    $text = 'Inactive';
                    $class = 'danger';
                @endphp
                @if (isset($team->managerDetail) && $team->managerDetail->status == 1)
                    @php
                        $text = 'Active';
                        $class = 'success';
                    @endphp
                @endif
                <div class="badge badge-light-{{ $class }} fw-bold">
                    {{ $text }}</div>
            </td>

            <td>{{ Utility::convertDmyWith12HourFormat(isset($team->managerDetail) ? $team->managerDetail->created_at : '') }}
            </td>
            <td><span class="badge bg-success">Manager</span></td>
        </tr>
        @forelse ($teamDetail as $key=>$team)
            <tr>
                <td>{{ $teamDetail->firstItem() + $key +1 }}</td>
                <td>
                    @php $route = route('sale-employee-view',isset($team->userDetail)?$team->userDetail->id:1) @endphp
                    @if (Auth()->user() !== null && Auth()->user()->role_id == 1)
                        @php $route = route('employees.show',isset($team->userDetail)?$team->userDetail->id:1) @endphp
                    @endif
                    <a href="{{ $route }}" class="pre-agro-emp">
                        {{ isset($team->userDetail) ? $team->userDetail->name : '-' }}</a>
                </td>
                <td>{{ $team->all_order_count }}</td>
                <td>{{ $team->pending_order_count }}</td>
                <td>{{ $team->on_delivery_order_count }}</td>
                <td>{{ $team->confirm_order_count }}</td>
                <td>{{ $team->delivered_order_count }}</td>
                <td>{{ $team->cancel_order_count }}</td>
                <td>{{ $team->return_order_count }}</td>
                <td>
                    @php $amount = 0; @endphp
                    @foreach ($team->deliveredOrder as $deliver)
                        @php $amount += $deliver->amount; @endphp
                    @endforeach
                    {{ $amount }}
                </td>
                <td>
                    @php
                        $text = 'Inactive';
                        $class = 'danger';
                    @endphp
                    @if (isset($team->userDetail) && $team->userDetail->status == 1)
                        @php
                            $text = 'Active';
                            $class = 'success';
                        @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat(isset($team->userDetail) ? $team->userDetail->created_at : '') }}
                </td>
                <td class="">
                    @php
                        $userId = isset($team->userDetail) ? $team->userDetail->id : '';
                    @endphp
                    <a class="btn btn-icon btn-info w-30px h-30px me-3"
                        onclick="removeTeamMember({{ $team->team_id }},{{ $userId }})" href="#"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="Remove Member">
                        <i class="fa fa-times"></i>
                    </a>

                </td>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $teamDetail->links('pagination::bootstrap-5') }}
</div>
