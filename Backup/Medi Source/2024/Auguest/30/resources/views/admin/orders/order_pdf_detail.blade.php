@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">Invoice Order Detail</h2>
                        <a href="{{ route('admin.orders') }}" class="btn btn-secondary ms-auto">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('store.order.pdf.detail') }}" method="post" id="order_pdf_detail_form">
                        @csrf
                        <div class="checkout-inner">
                            <section class="checkout-form">
                                <div class="container">
                                    <div class="mb-3" id="">
                                        <div class="row">
                                            <input type="hidden" name="order_id" value="{{ $id }}" id="order_id">
                                            <div class="col-6">
                                                <label class="form-label">P O Number</label>
                                                <input type="text" name="p_o_number" class="form-control"
                                                    placeholder="PO Number"
                                                    value="{{ old('p_o_number', isset($orderPdf) ? $orderPdf->p_o_number : '') }}">
                                                @if ($errors->has('p_o_number'))
                                                    <span class="text-danger">{{ $errors->first('p_o_number') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Terms</label>
                                                <input type="text" class="form-control" name="terms" id="terms"
                                                    placeholder="Terms"
                                                    value="{{ old('terms', isset($orderPdf) ? $orderPdf->terms : '') }}">
                                                @if ($errors->has('terms'))
                                                    <span class="text-danger">{{ $errors->first('terms') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">REP</label>
                                                <input type="text" class="form-control" name="rep" id="rep"
                                                    placeholder="rep"
                                                    value="{{ old('rep', isset($orderPdf) ? $orderPdf->rep : '') }}">
                                                @if ($errors->has('rep'))
                                                    <span class="text-danger">{{ $errors->first('rep') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Account Number</label>
                                                <input type="text" class="form-control" name="account_number"
                                                    id="account_number" placeholder="Account Number"
                                                    value="{{ old('account_number', isset($orderPdf) ? $orderPdf->account_number : '') }}">
                                                @if ($errors->has('account_number'))
                                                    <span class="text-danger">{{ $errors->first('account_number') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Requested Ship</label>
                                                <input type="text" class="form-control" name="requested_ship"
                                                    id="requested_ship" placeholder="Requested Ship"
                                                    value="{{ old('requested_ship', isset($orderPdf) ? $orderPdf->requested_ship : '') }}">
                                                @if ($errors->has('requested_ship'))
                                                    <span class="text-danger">{{ $errors->first('requested_ship') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Ship Via</label>
                                                <input type="text" class="form-control" name="ship_via" id="ship_via"
                                                    placeholder="Ship Via"
                                                    value="{{ old('ship_via', isset($orderPdf) ? $orderPdf->ship_via : '') }}">
                                                @if ($errors->has('ship_via'))
                                                    <span class="text-danger">{{ $errors->first('ship_via') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <h3 class="mb-3">Product Information</h3>
                                    <div class="mb-3" id="">
                                        @if (isset($orderPdf))
                                            @foreach ($orderPdf->orderProductPdfDetail as $item)
                                                <div class="row">
                                                    <div class="col-4">
                                                        <label class="form-label">Package Name</label>
                                                        <input type="text" class="form-control" name="package_name[]"
                                                            placeholder="Package Name" value="{{ $item->package_name }}"
                                                            readonly>
                                                        <input type="hidden" class="form-control" name="product_id[]"
                                                            value="{{ $item->product_id }}" readonly>
                                                        <input type="hidden" class="form-control" name="order_item_id[]"
                                                            value="{{ $item->order_item_id }}" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Lot Number</label>
                                                        <input type="text" class="form-control" name="lot_number[]"
                                                            placeholder="Lot Nnumber" value="{{ $item->lot_number }}">
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Lot</label>
                                                        <input type="text" class="form-control" name="lot[]"
                                                            placeholder="lot" value="{{ $item->lot }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            @foreach ($orderItem as $item)
                                                <div class="row">
                                                    <div class="col-4">
                                                        <label class="form-label">Package Name</label>
                                                        <input type="text" class="form-control" name="package_name[]"
                                                            placeholder="Package Name" value="{{ $item->package_name }}"
                                                            readonly>
                                                        <input type="hidden" class="form-control" name="product_id[]"
                                                            value="{{ $item->product_id }}" readonly>
                                                        <input type="hidden" class="form-control" name="order_item_id[]"
                                                            value="{{ $item->id }}" readonly>
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Lot Number</label>
                                                        <input type="text" class="form-control" name="lot_number[]"
                                                            placeholder="Lot Nnumber">
                                                    </div>
                                                    <div class="col-4">
                                                        <label class="form-label">Lot</label>
                                                        <input type="text" class="form-control" name="lot[]"
                                                            placeholder="lot">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    @if (PermissionHelper::checkUserPermission('Order Generate Invoice'))
                                        <button type="button" class="btn btn-icon btn-info" onclick="generateInvoice()"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Invoice">
                                            Generate Invoice</button>
                                    @endif
                                </div>
                            </section>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var exportFile = "{{ route('generate-invoice','id') }}";
        function generateInvoice(){
            var id = $('#order_id').val();
            var formData = $('#order_pdf_detail_form').serialize();
            var url = exportFile.replace('id',id);
            window.open(url + '?data=' + formData + '&id=' + id + '&type=function', '_blank');
        }
    </script>
@endsection