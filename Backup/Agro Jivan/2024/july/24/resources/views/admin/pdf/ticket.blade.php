<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info Sheet PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
    <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Ticket List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr >
                <th class="table">Ticket Type</th>
                <th class="table">Subject</th>
                <th class="table">Description</th>
                <th class="table">Status</th>
                <th class="table">Created AT</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($ticketList as $key=>$ticket)
                <tr>
                    <td class="table">{{ $ticket->ticket_type }}</td>
                    <td class="table">{{ $ticket->subject }}</td>
                    <td class="table">{{ $ticket->description }}</td>
                    <td class="table">@php
                        $text = 'Inactive';
                    @endphp
                        @if ($ticket->status == 1)
                            @php
                                $text = 'Active';
                            @endphp
                        @endif
                        {{ $text }}
                    </td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($ticket->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
