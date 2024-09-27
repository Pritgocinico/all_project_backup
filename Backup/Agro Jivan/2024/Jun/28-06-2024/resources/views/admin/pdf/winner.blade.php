<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Holiday PDF</title>
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
    <h5 style="text-align: center">Holiday List</h5>

    <table id="example" class="table" style="width:100%">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="table">No</th>
                <th class="table">Winner Name</th>
                <th class="table">Order</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($winnerData as $key=>$winner)
                <tr>
                    <td class="table">{{ $key+1 }}</td>
                    <td class="table">{{ $winner['winner']}}</td>
                    <td class="table">{{$winner['order']}}</td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="3">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
