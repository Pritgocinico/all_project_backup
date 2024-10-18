@extends('admin.partials.header', ['accesses' => $accesses, 'active' => ''])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center mb-5">
                    <div class="col">
                        <h1 class="ls-tight">Business Setting</h1>
                    </div>
                </div>
                <div class="vstack gap-3 gap-xl-6">
                    <div class="row row-cols-xl-12 g-3 g-xl-12 mp_business_card_outer">
                        @foreach ($departmentList as $depart)
                            <div class="col-xl-4 mp_business_card_inner">


                                <a href="{{ route('business.setting.view', $depart->id) }}">
                                    <div class="card bg-style1" style="height:350px;">
                                        <div class="p-7">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <h6 class="text-limit text-white mb-3">{{ $depart->name }}</h6>
                                                </div>
                                                <div class="col-md-2 text-end">
                                                    <i class="fa-solid fa-pen-to-square me-3 text-white"></i>
                                                </div>
                                            </div>
                                            @if (isset($depart->businessSettingDetail))
                                            <div class="text-sm fw-semibold mt-3 text-white"><b>Company Name:- </b>
                                                {{ $depart->businessSettingDetail->company_name }}</div>
                                            <div class="text-sm fw-semibold mt-3 text-white"><b>GST Number:- </b>
                                                {{ $depart->businessSettingDetail->gst_number }}</div>
                                            <div class="text-sm fw-semibold mt-3 text-white"><b>Support Phone Number:- </b>
                                                {{ $depart->businessSettingDetail->support_phone }}</div>
                                            <div class="text-sm fw-semibold mt-3 text-white"><b>Address:- </b>
                                                {{ $depart->businessSettingDetail->address }}</div>
                                            <div class="text-sm fw-semibold mt-3 text-white"><b>Description:- </b>
                                                {{ $depart->businessSettingDetail->description }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
