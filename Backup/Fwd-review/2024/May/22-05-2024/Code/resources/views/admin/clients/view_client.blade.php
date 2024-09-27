@extends('admin.layouts.app')

@section('content')
<style>
    body {
  background: #232323;
}
.accordion{
  margin: 40px 0;
}
.accordion .item {
    border: none;
    margin-bottom: 50px;
    background: none;
}
.t-p{
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
            <div class="pe-4 fs-5">View Client</div>
            <div class="ms-auto">
                <a href="{{route('admin.clients')}}" class="btn gc_btn">Go Back</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                 <div class="pe-4 fs-5 mb-3">Client Details  </div>
                    <div class="mt-4">
                        <label class="form-label">Client Name </label>
                        <h6>{{ $client->name }}</h6>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Client Email </label>
                        <h6>{{ $client->email }}</h6>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Client Phone </label>
                        <h6>{{ $client->phone }}</h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pe-4 fs-5 mb-3">Payment Details </div>
                    <div class="container">
                        <div class="accordion" id="accordionExample">
                            <div class="item">
                                <div class="item-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Details
                                            <i class="fa fa-angle-down gc_btn"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                    data-parent="#accordionExample">
                                    <div class="t-p row">
                                        <div class="col-md-6">
                                            <label class="form-label">Payment Date </label>
                                            <h6>2024-05-01</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Payment ID </label>
                                            <h6>2024-05-01</h6>
                                        </div>
                                    </div>
                                    <div class="t-p row">
                                        <div class="col-md-6">
                                            <label class="form-label">Subscription Start Date </label>
                                            <h6>2024-05-01</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Subscription End Date </label>
                                            <h6>2024-05-01</h6>
                                        </div>
                                    </div>
                                    <div class="t-p row">
                                        <div class="col-md-6">
                                            <label class="form-label">Transaction ID </label>
                                            <h6>123456789</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Plan Amount </label>
                                            <h6>$ 2000</h6>
                                        </div>
                                    </div>
                                    <div class="t-p row">
                                        <div class="col-md-6">
                                            <label class="form-label">Plan Name </label>
                                            <h6>Pro Plan</h6>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">On Boarded </label>
                                            <h6>Manual On Boarded</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
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