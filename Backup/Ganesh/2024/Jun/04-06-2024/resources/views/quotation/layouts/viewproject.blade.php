@extends('quotation.layouts.app')

@section('content')
    <div class="project_progressive_form">
        <div id="msform">
            <ul id="progressbar">
                <li>
                    <a class="@if(\Request::route()->getName() == 'quotation_view.selection' || \Request::route()->getName() == 'quotation_view.project' || \Request::route()->getName() == 'quotation_view.measurement' || \Request::route()->getName() == 'quotation_view.material' || \Request::route()->getName() == 'quotation_view.quotation' || \Request::route()->getName() == 'quotation_view.workshop' || \Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{route('quotation_view.progressbar.project', $projects->id)}}">Projects</a>
                </li>
                <li>
                    <a class="@if(\Request::route()->getName() == 'quotation_view.selection' || \Request::route()->getName() == 'quotation_view.measurement' || \Request::route()->getName() == 'quotation_view.material' || \Request::route()->getName() == 'quotation_view.quotation' || \Request::route()->getName() == 'quotation_view.workshop' || \Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{route('quotation_view.measurement', $projects->id)}}">Measurement</a>
                </li>
                <li>
                    <a class="@if(\Request::route()->getName() == 'quotation_view.selection' || \Request::route()->getName() == 'quotation_view.quotation' || \Request::route()->getName() == 'quotation_view.workshop' || \Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.material' || \Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{ !empty($measurements) ? route('quotation_view.quotation', $projects->id) : '#' }}">Quotation</a>
                </li>
                @if($projects->status == 0)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'quotation_view.workshop' || \Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{ !empty($quotations) ? route('quotation_view.selection', $projects->id) : '#' }}">Confirmation</a>
                    </li>
                @endif
                @if($projects->status == 1 || $projects->status == 2)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'quotation_view.material' || \Request::route()->getName() == 'quotation_view.workshop' || \Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{route('quotation_view.material', $projects->id)}}">Purchase</a>
                    </li>
                    <li>
                        <a class="@if(\Request::route()->getName() == 'quotation_view.workshop' || \Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.complete') {{ !empty($quotations) ? 'active' : 'disabled' }} @endif" href="{{ !empty($quotations) ? route('quotation_view.workshop', $projects->id) : '#' }}">Workshop</a>
                    </li>
                    <li>
                        <a class="@if(\Request::route()->getName() == 'quotation_view.fitting' || \Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{route('quotation_view.fitting', $projects->id)}}">Site Fitting</a>
                    </li>
                @endif
                @if($projects->status == 2)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'quotation_view.complete') active @endif" href="{{route('quotation_view.complete', $projects->id)}}">Complete</a>
                    </li>
                @endif
            </ul>
            @yield('pages')
        </div>
        <div class="project_view_detail_right">
            @include('quotation.projects.view_project_info')
        </div>
    </div>
@endsection
