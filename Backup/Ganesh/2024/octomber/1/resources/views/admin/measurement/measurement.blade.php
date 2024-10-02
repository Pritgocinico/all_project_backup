@extends('admin.layouts.app')

@section('content')
        <div class="project">
            <div class="page-header d-md-flex justify-content-between">
                <div class="">
                    <h3 class="mb-0">Measurement</h3>
                </div>
                <div class="">
                    <a href="{{ route('admin.add_measurement') }}" class="btn btn-primary ms-auto">
                        <i class="sub-menu-arrow ti-plus me-2"></i> Add Measurement
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Project 1</td>
                                <td>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis, minima?</td>
                                <td><a href="#"><i class="fa fa-download"></i></a></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Project Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
        </div>
@endsection
@section('script')

@endsection

