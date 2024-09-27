@include('admin.partials.header')
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')
            <!-- content -->
            @yield('content')
        </div>
    </div>
@include('admin.partials.footer')