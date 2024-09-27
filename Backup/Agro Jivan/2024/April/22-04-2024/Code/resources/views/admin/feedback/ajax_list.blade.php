<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Order Id</th>
            <th class="min-w-125px">Rating</th>
            <th class="min-w-125px">Description</th>
            <th class="min-w-125px">Created At</th>
            @if (Auth()->user() !== null && Auth()->user()->role_id !== 1)
                <th class="min-w-125px">Action</th>
            @endif
        </tr>
    </thead>
    <tbody class="text-black-600 fw-semibold">
        @forelse ($feedbackList as $key=>$feedback)
            <tr>
            <td>{{ $feedbackList->firstItem() + $key }}</td>
                <td>{{ isset($feedback->orderDetail) ? $feedback->orderDetail->order_id : '-' }}</td>
                <td>
                    @for ($i = 0; $i < 5; $i++)
                        @if ($i < $feedback->rating)
                            <a class="star_font_size">
                                <i class="fa-solid fa-star text-warning"></i>
                            </a>
                        @else
                            <a class="star_font_size">
                                <i class="fa-solid fa-star text-black"></i>
                            </a>
                        @endif
                    @endfor
                </td>
                <td>
                    {{ $feedback->order_description }}
                </td>

                <td>{{ Utility::convertDmyWith12HourFormat($feedback->created_at) }}</td>
                <td>
                    @if (Auth()->user() !== null && Auth()->user()->role_id !== 1)
                        @php
                            $orderId = isset($feedback->orderDetail) ? $feedback->orderDetail->id : '';
                        @endphp
                        <button class="btn-sm btn btn-primary" name="button" id="button" value="Write Feedback"
                            onclick="openFeedbackModal('{{ $orderId }}')">Write Feedback</button>
                    @endif
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
    {{ $feedbackList->links('pagination::bootstrap-5') }}
</div>
