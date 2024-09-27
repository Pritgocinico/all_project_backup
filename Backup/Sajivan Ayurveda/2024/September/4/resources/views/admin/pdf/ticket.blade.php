<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ticket PDF</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <style>
        .table {
            border: 1px solid black;
            border-collapse: collapse;
        }
        body {
            font-family: "Lexend", sans-serif !important;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        @php
            $imagePath = public_path('assets/img/login/sajivan-logo.png');
            if(isset($setting) && $setting->logo !== null){
                $imagePath = public_path('storage/'.$setting->logo);
            }
            $imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data:' . mime_content_type($imagePath) . ';base64,' . $imageData;
        @endphp
    <img alt="Logo" src="{{$src}}"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Ticket List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Ticket Id</th>
            <th class="table">User Name</th>
            <th class="table">Department Name</th>
            <th class="table">Ticket Type</th>
            <th class="table">Subject</th>
            <th class="table">Description</th>
            <th class="table">Status</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse ($ticketList as $key=>$ticket)
                <tr>
                    <td class="table">{{ $i }}</td>
                    <td class="table">{{ $ticket->ticket_id }}</td>
                    <td class="table">{{ isset($ticket->userDetail) ? $ticket->userDetail->name : '' }}</td>
                    <td class="table">{{ isset($ticket->departmentDetail) ? $ticket->departmentDetail->name : '' }}</td>
                    <td class="table">{{ $ticket->ticket_type }}</td>
                    @php $subject = $ticket->subject @endphp
                    @if (strlen($ticket->subject) > 30)
                        @php $subject = substr($ticket->subject, 0, 30) . '...'; @endphp
                    @endif
                    <td class="table" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $ticket->subject }}">{{ $subject }}</td>
                    @php $description = $ticket->description @endphp
                    @if (strlen($ticket->description) > 30)
                        @php $description = substr($ticket->description, 0, 30) . '...'; @endphp
                    @endif
                    <td class="table" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $ticket->description }}">{{ $description }}</td>
                    <td class="table">
                        @php
                            $text = 'Open';
                            $color = 'danger';
                            if ($ticket->status == 0) {
                                $color = 'success';
                                $text = 'Close';
                            }
                        @endphp
                        <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                    </td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($ticket->created_at)}}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @empty
                <tr>
                    <td style="text-center" colspan="9">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
