        <!-- Page Sidebar Start-->
        <div class="sidebar-wrapper" data-layout="fill-svg">
            <div>
                <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid"
                            src="{{ URL::asset('settings/'.$setting->logo) }}" alt="" width="150"></a>
                    <div class="toggle-sidebar">
                        <svg class="sidebar-toggle">
                            <use href="{{url('/')}}/assets/svg/icon-sprite.svg#toggle-icon"></use>
                        </svg>
                    </div>
                </div>
                <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}"><img style="width:36px"
                            src="{{url('/')}}/assets/images/aarna-sidebar.png" alt="" ></a></div>
                <nav class="sidebar-main">
                    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                    <div id="sidebar-menu">
                        <ul class="sidebar-links" id="simple-bar">
                            <li class="back-btn"><a href="{{ route('admin.dashboard') }}"><img style="width:36px"
                                        src="{{url('/')}}/assets/images/aarna-sidebar.png" alt=""></a>
                                <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                        aria-hidden="true"></i></div>
                            </li>
                            <li class="pin-title sidebar-main-title">
                                <div>
                                    <h6>Pinned</h6>
                                </div>
                            </li>
                           
                            <li class="sidebar-list mt-3"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title link-nav"
                                    href="{{ route('admin.dashboard') }}">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-home"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-home"> </use>
                                    </svg><span>Dashboard</span>
                                    <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                                </a>
                            </li>
                            @if(Auth::user()->role == 3)
                            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title link-nav @if (
                                        \Request::route()->getName() == 'admin.policies' ||
                                            \Request::route()->getName() == 'admin.add.policy' ||
                                            \Request::route()->getName() == 'admin.edit_policy') active @endif"
                                    href="{{ route('admin.policies') }}">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-editors"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-editors"> </use>
                                    </svg><span>Manage Policy</span>
                                    <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                                </a>
                            </li>
                            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title link-nav @if (\Request::route()->getName() == 'admin.reports') active @endif"
                                    href="{{ route('admin.reports') }}">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-layout"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-layout"> </use>
                                    </svg><span>Reports</span>
                                    <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                                </a>
                            </li>
                            @else
                           
                            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title @if (
                                    \Request::route()->getName() == 'admin.categories' ||
                                        \Request::route()->getName() == 'admin.edit_category' ||
                                        \Request::route()->getName() == 'admin.sub_categories' ||
                                        \Request::route()->getName() == 'admin.edit_subcategory' ||
                                        \Request::route()->getName() == 'admin.users' ||
                                        \Request::route()->getName() == 'admin.agent.payout' ||
                                        \Request::route()->getName() == 'admin.add.user' ||
                                        \Request::route()->getName() == 'admin.edit.user' ||
                                        \Request::route()->getName() == 'admin.companies' ||
                                        \Request::route()->getName() == 'admin.add.company' ||
                                        \Request::route()->getName() == 'admin.edit_company' ||
                                        \Request::route()->getName() == 'admin.tp_calculationparameter' ||
                                        \Request::route()->getName() == 'admin.sourcing.agents' ||
                                        \Request::route()->getName() == 'admin.edit_agent' ||
                                        \Request::route()->getName() == 'admin.add.agent' ||
                                        \Request::route()->getName() == 'admin.customers' ||
                                        \Request::route()->getName() == 'admin.add.customer' ||
                                        \Request::route()->getName() == 'admin.edit_customer' ||
                                        \Request::route()->getName() == 'admin.payout.list' ||
                                        \Request::route()->getName() == 'admin.plans' ||
                                        \Request::route()->getName() == 'admin.add.plan' ||
                                        \Request::route()->getName() == 'admin.edit_plan' ||
                                        \Request::route()->getName() == 'admin.payout.reports' ||
                                        \Request::route()->getName() == 'admin.edit_payout_list' ||
                                        \Request::route()->getName() == 'admin.view.payout' ||
                                        \Request::route()->getName() == 'user.permission' ||
                                        \Request::route()->getName() == 'admin.generate.payout') active @endif" href="#">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-learning"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-learning"></use>
                                    </svg><span>Management </span></a>
                                <ul class="sidebar-submenu">
                                @if(Auth::user()->role == 1)
                                        <li>
                                            <a href="{{ route('admin.users') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.users' ||
                                                        \Request::route()->getName() == 'admin.add.user' ||
                                                        \Request::route()->getName() == 'admin.edit.user') active @endif">
                                                        Users
                                            </a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->role == 1)
                                        <li>
                                            <a href="{{ route('admin.categories') }}"
                                                class="@if (\Request::route()->getName() == 'admin.categories' || \Request::route()->getName() == 'admin.edit_category') active @endif">
                                                Categories
                                            </a>
                                    @endif
                                    @if(Auth::user()->role == 1)
                                        <li>
                                            <a href="{{ route('admin.sub_categories') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.sub_categories' ||
                                                        \Request::route()->getName() == 'admin.edit_subcategory' ||
                                                        \Request::route()->getName() == 'admin.tp_calculationparameter') active @endif">
                                                        Sub Categories
                                            </a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->id == 1)
                                        <li>
                                            <a href="{{ route('admin.companies') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.companies' ||
                                                        \Request::route()->getName() == 'admin.add.company' ||
                                                        \Request::route()->getName() == 'admin.edit_company') active @endif">
                                                        Insurance Company
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.sourcing.agents') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.sourcing.agents' ||
                                                        \Request::route()->getName() == 'admin.edit_agent' ||
                                                        \Request::route()->getName() == 'admin.add.agent' ||
                                                        \Request::route()->getName() == 'admin.agent.payout') active @endif">
                                                        Sourcing Agents
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.customers') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.customers' ||
                                                        \Request::route()->getName() == 'admin.add.customer' ||
                                                        \Request::route()->getName() == 'admin.edit_customer') active @endif">
                                                        Customers
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.plans') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.plans' ||
                                                        \Request::route()->getName() == 'admin.add.plan' ||
                                                        \Request::route()->getName() == 'admin.edit_plan') active @endif">
                                                        Manage Plan
                                            </a>
                                        </li>
                                    @else
                                        @foreach($permissions as $permission)
                                            @if($permission->capability == 'company-view-global' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.companies') }}"
                                                        class="@if (
                                                            \Request::route()->getName() == 'admin.companies' ||
                                                                \Request::route()->getName() == 'admin.add.company' ||
                                                                \Request::route()->getName() == 'admin.edit_company') active @endif">
                                                                Insurance Company
                                                    </a>
                                                </li>
                                            @endif
                                            @if($permission->capability == 'agent-own-view' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.sourcing.agents') }}"
                                                        class="@if (
                                                            \Request::route()->getName() == 'admin.sourcing.agents' ||
                                                                \Request::route()->getName() == 'admin.edit_agent' ||
                                                                \Request::route()->getName() == 'admin.add.agent' ||
                                                                \Request::route()->getName() == 'admin.agent.payout') active @endif">
                                                                Sourcing Agents
                                                    </a>
                                                </li>
                                            @endif
                                            @if($permission->capability == 'customer-global-view' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.customers') }}"
                                                        class="@if (
                                                            \Request::route()->getName() == 'admin.customers' ||
                                                                \Request::route()->getName() == 'admin.add.customer' ||
                                                                \Request::route()->getName() == 'admin.edit_customer') active @endif">
                                                                Customers
                                                    </a>
                                                </li>
                                            @endif
                                            @if($permission->capability == 'plan-own-view' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.plans') }}"
                                                        class="@if (
                                                            \Request::route()->getName() == 'admin.plans' ||
                                                                \Request::route()->getName() == 'admin.add.plan' ||
                                                                \Request::route()->getName() == 'admin.edit_plan') active @endif">
                                                                Manage Plan
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                    @if(Auth::user()->role == 1)
                                        <li> 
                                            <a href="{{ route('admin.payout.list') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.payout.list' ||
                                                        \Request::route()->getName() == 'admin.edit_payout_list' ||
                                                        \Request::route()->getName() == 'admin.view.payout' ||
                                                        \Request::route()->getName() == 'admin.create.payout' ||
                                                        \Request::route()->getName() == 'admin.generate.payout') active @endif">
                                                        Manage Payout
                                            </a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->role == 1)
                                        <li> 
                                            <a href="{{ route('admin.payout.reports') }}"
                                                class="@if (\Request::route()->getName() == 'admin.payout.reports') active @endif">
                                                Payout Report
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>            
                            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title @if (
                                    \Request::route()->getName() == 'admin.business.source' ||
                                        \Request::route()->getName() == 'admin.covernote' ||
                                        \Request::route()->getName() == 'admin.policies' ||
                                        \Request::route()->getName() == 'admin.add.covernote' ||
                                        \Request::route()->getName() == 'admin.edit_covernote' ||
                                        \Request::route()->getName() == 'admin.add.policy' ||
                                        \Request::route()->getName() == 'admin.edit_policy' ||
                                        \Request::route()->getName() == 'admin.cancelled.policies') active @endif" href="#">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-editors"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-editors"></use>
                                    </svg><span>Insurance  </span></a>
                                <ul class="sidebar-submenu" @if (
                                    \Request::route()->getName() == 'admin.business.source' ||
                                        \Request::route()->getName() == 'admin.covernote' ||
                                        \Request::route()->getName() == 'admin.policies' ||
                                        \Request::route()->getName() == 'admin.add.covernote' ||
                                        \Request::route()->getName() == 'admin.edit_covernote' ||
                                        \Request::route()->getName() == 'admin.add.policy' ||
                                        \Request::route()->getName() == 'admin.claims' ||
                                        \Request::route()->getName() == 'admin.edit_policy' ||
                                        \Request::route()->getName() == 'admin.cancelled.policies') style="display:block" @endif>
                                @if(Auth::user()->role == 1)
                                        <li>
                                            <a href="{{ route('admin.business.source') }}"
                                                class="@if (\Request::route()->getName() == 'admin.business.source') active @endif">
                                                Manage Business Source
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.covernote') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.covernote' ||
                                                        \Request::route()->getName() == 'admin.add.covernote' ||
                                                        \Request::route()->getName() == 'admin.edit_covernote') active @endif">
                                                        Manage Covernote
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.policies') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.policies' ||
                                                        \Request::route()->getName() == 'admin.add.policy' ||
                                                        \Request::route()->getName() == 'admin.edit_policy') active @endif">
                                                        Manage Policy
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.claims-data') }}"
                                                class="@if (
                                                    \Request::route()->getName() == 'admin.claims') active @endif">
                                                        Manage Claims
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('admin.cancelled.policies') }}"
                                                class="@if (\Request::route()->getName() == 'admin.cancelled.policies') active @endif">
                                                Cancelled Policy
                                            </a>
                                        </li>
                                        <li> 
                                            <a href="{{ route('admin.reports') }}"
                                                class="@if (\Request::route()->getName() == 'admin.reports') active @endif">
                                                Reports
                                            </a>
                                        </li>
                                    @else
                                        @foreach($permissions as $permission)
                                            @if($permission->capability == 'source-global-view' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.business.source') }}"
                                                        class="@if (\Request::route()->getName() == 'admin.business.source') active @endif">
                                                        Manage Business Source
                                                    </a>
                                                </li>
                                            @endif
                                            @if($permission->capability == 'covernote-own-view' && $permission->value == 1)
                                                <li><a href="{{ route('admin.covernote') }}"
                                                        class="@if (
                                                            \Request::route()->getName() == 'admin.covernote' ||
                                                                \Request::route()->getName() == 'admin.add.covernote' ||
                                                                \Request::route()->getName() == 'admin.edit_covernote') active @endif">
                                                                Manage Covernote
                                                    </a>
                                                </li>
                                            @endif
                                            @if($permission->capability == 'policy-own-view' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.policies') }}"
                                                        class="@if (
                                                            \Request::route()->getName() == 'admin.policies' ||
                                                                \Request::route()->getName() == 'admin.add.policy' ||
                                                                \Request::route()->getName() == 'admin.edit_policy') active @endif">
                                                                Manage Policy
                                                    </a>
                                                </li>
                                            @endif
                                            @if($permission->capability == 'cancel_policy-own-view' && $permission->value == 1)
                                                <li>
                                                    <a href="{{ route('admin.cancelled.policies') }}"
                                                        class="@if (\Request::route()->getName() == 'admin.cancelled.policies') active @endif">
                                                        Cancelled Policy
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                </ul>
                            </li>                               
                            @endif                                     
                            @if(Auth::user()->role == 1)
                            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title link-nav @if (\Request::route()->getName() == 'admin.admin_payout') active @endif"
                                    href="{{ route('admin.admin_payout') }}">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-layout"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-layout"> </use>
                                    </svg><span>Admin Payout</span>
                                    <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                                </a>
                            </li>
                            <li class="sidebar-list"><i class="fa fa-thumb-tack"></i><a
                                    class="sidebar-link sidebar-title link-nav @if (\Request::route()->getName() == 'admin.admin_payout') active @endif"
                                    href="{{ route('admin.logs') }}">
                                    <svg class="stroke-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-layout"></use>
                                    </svg>
                                    <svg class="fill-icon">
                                        <use href="{{url('/')}}/assets/svg/icon-sprite.svg#fill-layout"> </use>
                                    </svg><span>Logs</span>
                                    <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                                </a>
                            </li>

                            @endif
                        </ul>
                    </div>
                    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                </nav>
            </div>
        </div>
        <!-- Page Sidebar Ends-->

        <div class="page-body">
          <div class="container-fluid">        
            <div class="page-title">
              <div class="row">
                <div class="col-sm-6 p-0">
                  <h3>@if(isset($page)){{$page}}@endif </h3>
                </div>
                <div class="col-sm-6 p-0">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">
                        <svg class="stroke-icon">
                          <use href="{{url('/')}}/assets/svg/icon-sprite.svg#stroke-home"></use>
                        </svg></a></li>
                    <li class="breadcrumb-item">@if(isset($page)){{$page}}@endif</li>
                    <li>
                        @if(\Request::route()->getName() != 'admin.dashboard') 
                        <a  onclick="history.back()" href="javascript:void(0)" class="btn btn-info ms-3"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a></li>
                        @endif
                  </ol>
                </div>
              </div>
            </div>
          </div>