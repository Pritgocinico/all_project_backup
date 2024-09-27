@extends('admin.layouts.app')

@section('content')
    <div class="project_progressive_form">
        <div id="msform">
            <ul id="progressbar">
                <li>
                    <a class="@if(\Request::route()->getName() == 'view.selection' || \Request::route()->getName() == 'view.project' || \Request::route()->getName() == 'view.measurement' || \Request::route()->getName() == 'view.material' || \Request::route()->getName() == 'view.quotation' || \Request::route()->getName() == 'view.workshop' || \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.complete') active @endif" href="{{route('view.progressbar.project', $projects->id)}}">Projects</a>
                </li>
                <li>
                    <a class="@if(\Request::route()->getName() == 'view.selection' || \Request::route()->getName() == 'view.measurement' || \Request::route()->getName() == 'view.material' || \Request::route()->getName() == 'view.quotation' || \Request::route()->getName() == 'view.workshop' || \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.complete') active @endif" href="{{route('view.measurement', $projects->id)}}">Measurement</a>
                </li>
                <li>
                    <a class="@if(\Request::route()->getName() == 'view.selection' || \Request::route()->getName() == 'view.quotation' || \Request::route()->getName() == 'view.workshop' || \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.material' || \Request::route()->getName() == 'view.complete') active @endif" href="{{ !empty($measurements) ? route('view.quotation', $projects->id) : '#' }}">Quotation</a>
                </li>
                @if($projects->status == 0)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'view.workshop' || \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.complete') active @endif" href="{{ !empty($quotations) ? route('view.selection', $projects->id) : '#' }}">Confirmation</a>
                    </li>
                @endif
                @if($projects->status == 1 || $projects->status == 2)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'view.material' || \Request::route()->getName() == 'view.workshop' || \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.complete') active @endif" href="{{route('view.material', $projects->id)}}">Purchase</a>
                    </li>
                    <li>
                        <a class="@if(\Request::route()->getName() == 'view.workshop' || \Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.complete') {{ !empty($quotations) ? 'active' : 'disabled' }} @endif" href="{{ !empty($quotations) ? route('view.workshop', $projects->id) : '#' }}">Workshop</a>
                    </li>
                    <li>
                        <a class="@if(\Request::route()->getName() == 'view.fitting' || \Request::route()->getName() == 'view.complete') active @endif" href="{{route('view.fitting', $projects->id)}}">Site Installation</a>
                    </li>
                @endif
                @if($projects->status == 2)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'view.complete') active @endif" href="{{route('view.complete', $projects->id)}}">Complete</a>
                    </li>
                @endif
            </ul>
            @yield('pages')
        </div>
        <div class="project_view_detail_right">
            @include('admin.projects.view_project_info')
        </div>
    </div>
@endsection
