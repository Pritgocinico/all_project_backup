<div id="menu" class="gc-sidebar open ">
    <div id="side" class=" ">
        <div class="p-3 sidebar_menu side-bar ">
            <div class="menu">
                @if(blank($business))
                    <div class="item @if(\Request::route()->getName() == 'client.dashboard') active @endif">
                        <a href="{{route('client.dashboard')}}" class="d-flex align-items-center">
                            <img src="{{url('/')}}/assets/Images/Sidebar_Icons/dashboard.png" alt="">
                            <span>Dashboard</span>
                        </a>
                    </div>
                @else
                    <div class="item @if(\Request::route()->getName() == 'client.funnel' || \Request::route()->getName() == 'client.dashboard') active @endif">
                        <a href="{{route('client.funnel')}}" class="d-flex align-items-center">
                            <img src="{{url('/')}}/assets/Images/Sidebar_Icons/dashboard.png" alt="">
                            <span>Funnel</span>
                        </a>
                    </div>
                    <div class="item @if(\Request::route()->getName() == 'client.reviews') active @endif">
                        <a href="{{route('client.reviews')}}" class="d-flex align-items-center">
                            <img src="{{url('/')}}/assets/Images/Sidebar_Icons/customer.png" alt="">
                            <span>Reviews</span>
                        </a>
                    </div>
                    <div class="item" @if(\Request::route()->getName() == 'client.report.analytic') style="display:block" @endif>
                        <a class="sub-btn d-flex align-items-center" >
                            <img src="{{url('/')}}/assets/Images/Sidebar_Icons/staff.png" alt="">
                            <span>Reports</span>
                            <i class="fas fa-angle-down dropdown"></i>
                        </a>
                        <div class="sub-menu " @if(\Request::route()->getName() == 'client.report.analytic' || \Request::route()->getName() == 'client.add.email') style="display:block" @endif>
                            <a href="{{route('client.report.analytic')}}" class="sub-item d-flex align-items-center @if(\Request::route()->getName() == 'client.report.analytic') active @endif">
                                <img src="{{url('/')}}/assets/Images/Sidebar_Icons/staff.png" alt="">
                                <span>Analytics</span>
                            </a>
                            <a href="{{route('client.report.generated')}}" class="sub-item d-flex align-items-center @if(\Request::route()->getName() == 'client.report.generated' || \Request::route()->getName() == 'client.add.email') active @endif">
                                <img src="{{url('/')}}/assets/Images/Sidebar_Icons/mail.png" alt="">
                                <span>Generated Reports</span>
                            </a>
                        </div>
                    </div>
                    <div class="item @if(\Request::route()->getName() == 'client.widgets') active @endif">
                        <a href="{{route('client.widgets')}}" class="d-flex align-items-center">
                            <img src="{{url('/')}}/assets/Images/Sidebar_Icons/widgets.png" alt="">
                            <span>Widgets</span>
                        </a>
                    </div>
                    <div class="item" @if(\Request::route()->getName() == 'client.recipients') style="display:block" @endif>
                        <a class="sub-btn d-flex align-items-center" >
                            <img src="{{url('/')}}/assets/Images/Sidebar_Icons/staff.png" alt="">
                            <span>Review Requests</span>
                            <i class="fas fa-angle-down dropdown"></i>
                        </a>
                        <div class="sub-menu " @if(\Request::route()->getName() == 'client.recipients' || \Request::route()->getName() == 'client.email.settings' || \Request::route()->getName() == 'client.add.email') style="display:block" @endif>
                            <a href="{{route('client.recipients')}}" class="sub-item d-flex align-items-center @if(\Request::route()->getName() == 'client.recipients') active @endif">
                                <img src="{{url('/')}}/assets/Images/Sidebar_Icons/staff.png" alt="">
                                <span>Recepients</span>
                            </a>
                            <a href="{{route('client.email.settings')}}" class="sub-item d-flex align-items-center @if(\Request::route()->getName() == 'client.email.settings' || \Request::route()->getName() == 'client.add.email') active @endif">
                                <img src="{{url('/')}}/assets/Images/Sidebar_Icons/mail.png" alt="">
                                <span>Email Settings</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<div  id="content" class="content_normal pt-md-4 p-3">
