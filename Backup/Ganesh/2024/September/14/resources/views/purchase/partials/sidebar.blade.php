<div class="content-wrapper">
    <div class="navigation">
        <div class="navigation-header">
            <span>Navigation</span>
            <a href="#">
                <i class="ti-close"></i>
            </a>
        </div>
        <div class="navigation-menu-body">
            <ul>
                <li>
                    <a class="@if (\Request::route()->getName() == 'purchase.dashboard') active @endif" href="{{ route('purchase.dashboard') }}">
                        <span class="nav-link-icon">
                            <i data-feather="pie-chart"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="@if (
                        \Request::route()->getName() == 'purchase_projects' ||
                            \Request::route()->getName() == 'purchase_addprojects' ||
                            \Request::route()->getName() == 'purchase_edit.project' ||
                            \Request::route()->getName() == 'purchase_view.project' ||
                            \Request::route()->getName() == 'purchase_view.measurement' ||
                            \Request::route()->getName() == 'purchase_view.purchase' ||
                            \Request::route()->getName() == 'purchase_view.workshop' ||
                            \Request::route()->getName() == 'purchase_view.fitting' ||
                            \Request::route()->getName() == 'purchase_view.material' ||
                            \Request::route()->getName() == 'purchase_view.lead' ||
                            \Request::route()->getName() == 'purchase_view.complete' ||
                            \Request::route()->getName() == 'purchase_view.completed.project') @if (isset($type) && $type == 'Project') active @endif @endif"
                        href="{{ route('purchase_projects') }}">
                        <span class="nav-link-icon">
                            <i data-feather="copy"></i>
                        </span>
                        <span>Projects</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchase_task-management') }}"
                        class="@if (
                            \Request::route()->getName() == 'purchase_task-management' ||
                                \Request::route()->getName() == 'purchase_addTask' ||
                                \Request::route()->getName() == 'purchase_editTask') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Task Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchase_logs') }}" class="@if (\Request::route()->getName() == 'purchase_logs') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="file"></i>
                        </span>
                        <span>Logs</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="content-body">
        <div class="content ">
