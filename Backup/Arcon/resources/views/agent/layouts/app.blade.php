@include('agent.partials.header')
    <div class="container-fluid">
        <div class="row">
            @include('agent.partials.sidebar')
            <!-- content -->
            @yield('content')
        </div>
    </div>
@include('agent.partials.footer')
