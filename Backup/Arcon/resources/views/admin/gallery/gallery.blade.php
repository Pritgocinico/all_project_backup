@extends('admin.layouts.app')

@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="mb-4 px-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="javascript:void(0);">Gallery</a>
            </li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="houmanity-card">
            <div class="card-body card-head">
                <div class="d-md-flex gap-4 align-items-center bg-white p-3">
                    <div class="d-none d-md-flex">All Images</div>
                    <div class="d-md-flex gap-4 align-items-center">
                        <form class="mb-3 mb-md-0">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <select class="form-select classic order-table">
                                        <option hidden>Sort by</option>
                                        <option value="desc">Desc</option>
                                        <option value="asc">Asc</option>
                                    </select>
                                </div>
                                <!-- <div class="col-md-5">
                                    <select class="form-select classic" id="maxRows">
                                        <option value="10" selected="selected">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select>
                                </div> -->
                            </div>
                        </form>
                    </div>
                    <div class="ms-auto d-flex arcon-user-inner-search-outer">
                        <form class="arcon-user-inner-search" action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control src d-none" id="search-table" placeholder="Search">
                                        <span class="search-btn mt-2 ms-2" type="button">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                          Add Image / Video
                        </button>
                        <!--<a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">-->
                        <!--    Add Photos-->
                        <!--</a>-->
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse" id="collapseExample">
            <div class="houmanity-card p-3">
                <div class="card p-3">
                    <div class="card-body m-3">
                        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="dropzone">
                            @csrf
                        </form>
                        <button class="btn btn-primary btn-icon mt-3" class="uploadFile" id="uploadFile">
                            Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="houmanity-card">
            {{-- <div class="row">
                @if(!blank($gallery))
                    @foreach ($gallery as $item)
                    <div class="col-md-4">
                        <div class="card">
                            <img src="{{url('/')}}/public/gallery/{{$item->photo}}" alt="" >
                        </div>
                    </div>
                    @endforeach
                @endif
            </div> --}}
            <div class="card-body table-responsive">
                <table id="" class="table table-custom rwd-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!blank($gallery))
                            @foreach ($gallery as $item)
                                <tr>
                                    <td data-th="Image"><img src="{{url('/')}}/public/gallery/{{$item->photo}}" alt="" width="80px"></td>
                                    <td data-th="Name">
                                        {{$item->photo_name}}<br>
                                        @if(!blank($item->link))
                                        <p><span class="fw-bold">Link : </span><a href="{{$item->link}}">{{$item->link}}</a></p>
                                        @endif
                                    </td>
                                    <td data-th="Type">
                                        @if($item->gallery_type == 1)
                                            Image
                                        @else
                                            Video
                                        @endif
                                    </td>
                                    <td data-th="Created At">{{$item->created_at}}</td>
                                    <td data-th="Action" class="text-md-end">
                                        <a href="javascript:void(0);" class="btn btn-danger delete-btn" data-id="{{$item->id}}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="3">Photos not found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Gallery Image / Video</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{route('admin.add_gallery')}}" enctype="multipart/form-data"> 
      @csrf
          <div class="modal-body">
             <div class="row g-3">
                <div class="col-md-12">
                    <div class="mt-2">
                        <label>Select Gallery Type</label>
                        <select name="gallery_type" class="form-control gallery_type">
                            <option value="1">Image</option>
                            <option value="2">Video</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mt-2">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>
                    <div class="mt-2 video d-none">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control">
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone(".dropzone", {
        autoProcessQueue: false,
        parallelUploads: 10,
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.svg",
        success: function (file, response) {
            Swal.fire({
                title: 'Uploaded Successfully!',
                text: "File has been uploaded.",
                icon: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ok'
                }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        },
        error: function (file, response) {
            return false;
        }
    });

    $('#uploadFile').click(function(){
        myDropzone.options.autoProcessQueue = true;
       myDropzone.processQueue();
    });
    myDropzone.on("queuecomplete", function() {
        myDropzone.options.autoProcessQueue = false;
    });
</script>
<script>
    $(document).ready(function(){

        $(".add_photos_btn").click(function() {
            $("#uploadImage").toggle();
        });
        $(document).on('change','.gallery_type',function(){
            if($(this).val() == 1){
                $('.video').addClass('d-none');
            }else{
                $('.video').removeClass('d-none');
            }
        })
        $(document).on('click','.delete-btn',function(){
            var id = $(this).attr('data-id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.gallery', '')}}"+"/"+id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Photo has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            }
            });
        });
    });
</script>
@endsection
