@extends('purchase.layouts.app')

@section('content')
    <div class="project_progressive_form">
        <div id="msform">
            <ul id="progressbar">
                <li>
                    <a class="@if(\Request::route()->getName() == 'purchase_view.selection' || \Request::route()->getName() == 'purchase_view.quotation' || \Request::route()->getName() == 'purchase_view.workshop' || \Request::route()->getName() == 'purchase_view.fitting' || \Request::route()->getName() == 'purchase_view.material' || \Request::route()->getName() == 'purchase_view.complete') active @endif" href="{{ !empty($measurements) ? route('purchase_view.quotation', $projects->id) : '#' }}">Quotation</a>
                </li>
                @if($projects->status == 1 || $projects->status == 2)
                    <li>
                        <a class="@if(\Request::route()->getName() == 'purchase_view.material' || \Request::route()->getName() == 'purchase_view.workshop' || \Request::route()->getName() == 'purchase_view.fitting' || \Request::route()->getName() == 'purchase_view.complete') active @endif" href="{{route('purchase_view.material', $projects->id)}}">Purchase</a>
                    </li>
                    <li>
                        <a class="@if(\Request::route()->getName() == 'purchase_view.workshop' || \Request::route()->getName() == 'purchase_view.fitting' || \Request::route()->getName() == 'purchase_view.complete') {{ !empty($purchase) ? 'active' : 'disabled' }} @endif" href="{{ !empty($purchase) ? route('purchase_view.workshop', $projects->id) : '#' }}">Workshop</a>
                    </li>
                    <li>
                        <a class="@if(\Request::route()->getName() == 'purchase_store_qa_data' || \Request::route()->getName() == 'purchase_store_qa_question' || \Request::route()->getName() == 'purchase_view.complete') {{ !empty($purchase) ? 'active' : 'disabled' }} @endif" href="{{ !empty($purchase) ? route('purchase_view.qa.question', $projects->id) : '#' }}">QA</a>
                    </li>
                @endif
            </ul>
            @yield('pages')
        </div>
        <div class="project_view_detail_right">
            @include('purchase.projects.view_project_info')
        </div>
    </div>
@endsection
