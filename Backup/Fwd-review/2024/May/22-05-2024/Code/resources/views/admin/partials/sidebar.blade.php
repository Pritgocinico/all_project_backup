<div id="menu" class="gc-sidebar open ">
    <div id="side" class=" ">
        <div class="p-3 sidebar_menu side-bar ">
            <h6 class=" pb-2 py-3"><span class="">Company</span></h6>
            <div class="menu">
                <div class="item @if (\Request::route()->getName() == 'admin.dashboard') active @endif">
                    <a href="{{ url('/') }}" class="d-flex align-items-center">
                        <img src="{{ url('/') }}/assets/Images/Sidebar_Icons/dashboard.png" alt="">
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="item @if (
                    \Request::route()->getName() == 'admin.clients' ||
                        \Request::route()->getName() == 'admin.add.client' ||
                        \Request::route()->getName() == 'admin.edit.client') active @endif">
                    <a href="{{ route('admin.clients') }}" class="d-flex align-items-center">
                        <img src="{{ url('/') }}/assets/Images/Sidebar_Icons/customer.png" alt="">
                        <span>Clients</span>
                    </a>
                </div>
                <div class="item @if (
                    \Request::route()->getName() == 'admin.business' ||
                        \Request::route()->getName() == 'admin.add.business' ||
                        \Request::route()->getName() == 'admin.edit.business') active @endif">
                    <a href="{{ route('admin.business') }}" class="d-flex align-items-center">
                        <img src="{{ url('/') }}/assets/Images/add-user.png" alt="">
                        <span>Business</span>
                    </a>
                </div>
                <div class="item @if (\Request::route()->getName() == 'admin.business-request') active @endif">
                    <a href="{{ route('admin.business-request') }}" class="d-flex align-items-center">
                        <img src="{{ url('/') }}/assets/Images/Sidebar_Icons/notification.png" alt="">
                        <span>Clients Request</span>
                    </a>
                </div>
                <div class="item @if (\Request::route()->getName() == 'admin.logs') active @endif">
                    <a href="{{ route('admin.logs') }}" class="d-flex align-items-center">
                        <img src="{{ url('/') }}/assets/Images/logs.png" alt="">
                        <span>Logs</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="content" class="content_normal pt-md-4 p-3">