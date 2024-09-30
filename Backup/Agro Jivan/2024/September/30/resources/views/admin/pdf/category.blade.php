<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category PDF</title>
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
    <h5 style="text-align: center">Category List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="table">Category Image</th>
                <th class="table">Category Name</th>
                <th class="table">Parent Category Name</th>
                <th class="table">Created At</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($categoryList as $key=>$category)
                <tr>
                    <td class="align-middle table">
                        @php $image = asset('public/assets/upload/category/default.jpg') @endphp
                        @if ($category->category_image !== null)
                            @php $image = asset('public/assets/upload/'.$category->category_image) @endphp
                        @endif
                        <img src="{{ $image }}" width="50px" alt="">
                    </td>
                    <td class="table">{{ $category->name }}</td>
                    <td class="table">{{ isset($category->categoryDetail) ? $category->categoryDetail->name : '' }}</td>
    
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($category->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
