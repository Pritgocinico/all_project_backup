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
                        \Request::route()->getName() == 'qa_projects' ||
                            \Request::route()->getName() == 'qa_addprojects' ||
                            \Request::route()->getName() == 'qa_edit.project' ||
                            \Request::route()->getName() == 'qa_view.project' ||
                            \Request::route()->getName() == 'qa_view.measurement' ||
                            \Request::route()->getName() == 'qa_view.purchase' ||
                            \Request::route()->getName() == 'qa_view.workshop' ||
                            \Request::route()->getName() == 'qa_view.fitting' ||
                            \Request::route()->getName() == 'qa_view.material' ||
                            \Request::route()->getName() == 'qa_view.lead' ||
                            \Request::route()->getName() == 'qa_view.complete' ||
                            \Request::route()->getName() == 'qa_view.completed.project') @if (isset($type) && $type == 'Project') active @endif @endif"
                        href="{{ route('qa_projects') }}">
                        <span class="nav-link-icon">
                            <i data-feather="copy"></i>
                        </span>
                        <span>Projects</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('qa_task-management') }}"
                        class="@if (
                            \Request::route()->getName() == 'qa_task-management' ||
                                \Request::route()->getName() == 'qa_addTask' ||
                                \Request::route()->getName() == 'qa_editTask') active @endif">
                        <span class="nav-link-icon">
                            <i data-feather="layers"></i>
                        </span>
                        <span>Task Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('qa_logs') }}" class="@if (\Request::route()->getName() == 'qa_logs') active @endif">
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
