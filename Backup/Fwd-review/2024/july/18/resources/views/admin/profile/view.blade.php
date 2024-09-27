@extends('admin.layouts.app')

@section('content')
    <style>
        body {
            background: #232323;
        }

        .accordion {
            margin: 40px 0;
        }

        .accordion .item {
            border: none;
            margin-bottom: 50px;
            background: none;
        }

        .t-p {
            color: rgb(193 206 216);
            padding: 40px 30px 0px 30px;
        }

        .accordion .item .item-header h2 button.btn.btn-link {
            background: #333435;
            color: white;
            border-radius: 0px;
            font-family: 'Poppins';
            font-size: 16px;
            font-weight: 400;
            line-height: 2.5;
            text-decoration: none;
        }

        .accordion .item .item-header {
            border-bottom: none;
            background: transparent;
            padding: 0px;
            margin: 2px;
        }

        .accordion .item .item-header h2 button {
            color: white;
            font-size: 20px;
            padding: 15px;
            display: block;
            width: 100%;
            text-align: left;
        }

        .accordion .item .item-header h2 i {
            float: right;
            font-size: 30px;
            color: #eca300;
            background-color: black;
            width: 60px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }

        button.btn.btn-link.collapsed i {
            transform: rotate(0deg);
        }

        button.btn.btn-link i {
            transform: rotate(180deg);
            transition: 0.5s;
        }
    </style>
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
                <div class="pe-4 fs-5">View Admin</div>
                <div class="ms-auto">
                    <a href="{{ route('admin.edit') }}" class="btn gc_btn">Edit</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="pe-4 fs-5 mb-3">Admin Details </div>
                        <div class="mt-4">
                            <label class="form-label">Admin Name </label>
                            <h6>{{ $client->name }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="mt-4">
                            <label class="form-label">Admin Email </label>
                            <h6>{{ $client->email }}</h6>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mt-4">
                            <label class="form-label">Admin Phone </label>
                            <h6>{{ $client->phone }}</h6>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
@endsection
