@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.update.admin_payout') }}" method="POST" class="row w-100"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-8">
                        <h4>Add Insurance Company</h4>
                        <div class="mt-3">
                            <label for="Parameters">
                                <p>Select Insurance Company</p>
                            </label>
                            <div class="row">
                                <div class="col-md-9">
                                    <select name="company" class="form-control" id="company">
                                        <option value="0" selected>Select Company...</option>
                                        @if (!blank($companies))
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}" @if (
                                                    $company->id == $payouts_data[0]['companies']['id'] )
                                                    selected
                                                    
                                                @endif>{{ $company->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="companyError text-danger"></span>
                                </div>
                                {{-- <div class="col-md-5">
                                <select name="subcategory" class="form-control" id="parameter">
                                    <option value="0" selected>Select Subcategory...</option>
                                    @if (!blank($sub_categories))
                                        @foreach ($sub_categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div> --}}
                                <div class="col-md-3">
                                    <div>
                                        <button type="button" class="btn btn-primary addCompany btn-sm">+ Add
                                            Company</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="companies-payout">
                            <?php
                            $i = 1000;
                            $j = 200;
                            ?>
                            @foreach ($payouts as $key => $payout)
                                <div class="payoutCompany company-{{ $payout['companies']['id'] }}"
                                    data-id="{{ $payout['companies']['id'] }}">
                                    <h5>{{ $payout['companies']['name'] }}</h5>
                                    <div class="company-data-{{ $payout['companies']['id'] }}">
                                        @foreach ($payout as $key_pay => $pay)
                                            <?php
                                            $i++;
                                            if($key_pay != 'companies'){
                                                $plans = DB::table('plans')->where('company',$pay->company)->select('id','name')->get();
                                        ?>
                                        
                                            <div class="row record-{{ $payout['companies']['id'] }}{{ $key_pay }}">
                                                <div class="col-md-6 form-floating mt-4">
                                                    <select class="form-control comCat com-{{ $i }}"
                                                        data-id="{{ $i }}"
                                                        name="company_category[{{ $pay->company }}][category][]">
                                                        <option value="0">select category / plan </option>
                                                        @foreach ($sub_categories as $item)
                                                            <option value="{{ $item->id }}"
                                                                @if ($pay->type == 'category') @if ($item->id == $pay->category) selected @endif
                                                                @endif
                                                                data-type="category">{{ $item->name }}</option>
                                                        @endforeach
                                                        @foreach ($plans as $item)
                                                            <option value="{{ $item->id }}"
                                                                @if ($pay->type == 'plan') @if ($item->id == $pay->category) selected @endif
                                                                @endif
                                                                data-type="plan">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" class="comCat-{{ $i }}"
                                                        name="company_category[{{ $pay->company }}][type][]"
                                                        value="{{ $pay->type }}">
                                                    <input type="hidden" class="comCat-{{ $i }}"
                                                        name="company_category[{{ $pay->company }}][id][]"
                                                        value="{{ $pay->id }}">
                                                    <label for="" class="form-label">Sub Category / Plan</label>
                                                </div>
                                                <div class="col-md-3 form-floating mt-4">
                                                    <select name="company_category[{{ $pay->company }}][payout_on][]"
                                                        class="form-control comCat-{{$i}}" required data-id="{{$i}}">';
                                                        <option value="">select Own Damage / Net Premium </option>
                                                        <option value="od"
                                                            @if ($pay->payout_on == 'od') selected @endif>Own Damage
                                                        </option>
                                                        <option value="np"
                                                            @if ($pay->payout_on == 'np') selected @endif>Net Premium
                                                        </option>

                                                    </select>
                                                    <label fssssor="" class="form-label">Own Damage/Net Premium</label>
                                                </div>
                                                <div class="col-md-3 form-floating mt-4">

                                                    <input type="text" class="form-control"
                                                        name="company_category[{{ $pay->company }}][payout][]"
                                                        min="0" value="{{ $pay->value }}">
                                                    <label fssssor="" class="form-label">Payout</label>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger mt-4 deleteCompanyRecord"
                                                        data-id="{{ $payout['companies']['id'] }}{{ $key_pay }}"
                                                        data-payout="{{ $pay->id }}">Remove</button>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        ?>
                                        @endforeach
                                    </div>
                                    <div class="row mt-3">
                                        <div>
                                            <button type="button" class="btn btn-primary addSubCategory"
                                                data-id="{{ $payout['companies']['id'] }}">Add New</button>
                                        </div>
                                    </div>
                                </div>
                                <?php $j++; ?>
                            @endforeach
                        </div>
                    </div>
                    <hr class="mt-3">
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.payoutCompany').each(function() {
                var com_id = $(this).data('id');
                $('#company option[value="' + com_id + '"]').prop('disabled', true);
            });
        });
        var i = 100;
        $(document).on('click', '.addCompany', function() {
            i++;
            $('.companyError').html('')
            var company = $('#company').val();
            if (company == 0) {
                $('.companyError').html('Please select company.');
            } else {
                $.ajax({
                    url: "{{ route('get_payout_company_subcategory', '') }}" + "/" + company,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var html = '<input type="hidden" name="type_number" value="' + i + '">';
                        html += '<div class="payoutCompany company-' + company + '" data-id="' + data
                            .company.id + '">';
                        html += '<h5>' + data.company.name + '</h5>';
                        html += '<div class="company-data-' + company + '">';
                        html += '<div class="row record-' + i + '">';
                        html += '<div class="col-md-5 form-floating mt-4">';
                        html += '<select class="form-control comCat com-' + i + '" data-id="' + i +
                            '" name="company_category[' + company + '][category][' + i + ']">';
                        html += '<option value="0">select category / plan </option>';
                        $.each(data.category, function(index, res) {
                            html += '<option value="' + res.id + '" data-type="category">' + res
                                .name + '</option>';
                        });
                        $.each(data.plans, function(index, res) {
                            html += '<option value="' + res.id + '" data-type="plan">' + res
                                .name + '</option>';
                        });
                        html += '</select>';
                        html += '<input type="hidden" class="comCat-' + i +
                            '" name="company_category[' + company + '][type][' + i +
                            ']" value="category">';
                        html += '<label for="" class="form-label">Sub Category / Plan</label>';
                        html += '</div>';
                        html += '<div class="col-md-3 form-floating mt-4">';
                        html += '<select name="company_category[' + company + '][payout_on][]" class="form-control comCat-' + i + '" required  data-id="' + i + '">';
                        html += '<option value="">select Own Damage / Net Premium </option>';
                        html += '<option value="od">Own Damage</option>';
                        html += '<option value="np">Net Premium </option>';

                        html += '</select>';
                        html += '<label for="" class="form-label">Own Damage/Net Premium</label>';
                        html += '</div>';
                        html += '<div class="col-md-2 form-floating mt-4">';
                        html += '<input type="text" class="form-control" name="company_category[' +
                            company + '][payout][' + i + ']" min="0" value="0">';
                        html += '<label for="" class="form-label">Payout</label>';
                        html += '</div>';
                        html += '<div class="col-md-2">';
                        html +=
                            '<button type="button" class="btn btn-danger deleteRecord mt-4" data-id="' +
                            i + '">Remove</button>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="row mt-3">';
                        html +=
                            '<div class="d-flex"><button type="button" class="btn btn-primary addSubCategory" data-id="' +
                            company + '">Add New</button>';
                        html +=
                            '<button type="button" class="btn btn-danger removeCompany ms-2" data-id="' +
                            company + '">Remove</button>';
                        html += '</div>';
                        html += '</div>';
                        $('.companies-payout').append(html);
                        $('.payoutCompany').each(function() {
                            var com_id = $(this).data('id');
                            $('#company option[value="' + com_id + '"]').prop('disabled', true);
                        });
                    }
                });
            }
        });
        $(document).on('click', '.removeCompany', function() {
            var id = $(this).data('id');
            $('.company-' + id).remove();
            $('#company option[value="' + id + '"]').prop('disabled', false);
        });
        $(document).on('click', '.addSubCategory', function() {
            i++;
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('get_payout_company_subcategory', '') }}" + "/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var html = '';
                    html += '<div class="row record-' + i + '">';
                    html += '<div class="col-md-6 form-floating mt-4">';
                    html += '<select class="form-control comCat com-' + i + '" data-id="' + i +
                        '" name="company_category[' + id + '][category][' + i + ']">';
                    html += '<option value="0">select category / plan </option>';
                    $.each(data.category, function(index, res) {
                        html += '<option value="' + res.id + '" data-type="category">' + res
                            .name + '</option>';
                    });
                    $.each(data.plans, function(index, res) {
                        html += '<option value="' + res.id + '" data-type="plan">' + res.name +
                            '</option>';
                    });
                    html += '</select>';
                    html += '<input type="hidden" class="comCat-' + i + '" name="company_category[' +
                        id + '][type][' + i + ']" value="category">';
                    html += '<label for="" class="form-label">Sub Category / Plan</label>';
                    html += '</div>';
                    html += '<div class="col-md-3 form-floating mt-4">';
                    html += '<select name="company_category[' + company + '][payout_on][]" class="form-control comCat-' + i + '" required  data-id="' + i + '">';
                    html += '<option value="">select Own Damage / Net Premium </option>';
                    html += '<option value="od">Own Damage</option>';
                    html += '<option value="np">Net Premium </option>';

                    html += '</select>';
                    html += '<label for="" class="form-label">Own Damage/Net Premium</label>';
                    html += '</div>';
                    html += '<div class="col-md-3 form-floating mt-4">';
                    html += '<input type="text" class="form-control" name="company_category[' + id +
                        '][payout][' + i + ']" min="0" value="0">';
                    html += '<label for="" class="form-label">Payout</label>';
                    html += '</div>';
                    html += '<div class="col-md-2">';
                    html += '<button type="button" class="btn btn-danger deleteRecord mt-4" data-id="' +
                        i + '">Remove</button>';
                    html += '</div>';
                    html += '</div>';
                    $('.company-data-' + id).append(html);
                }
            });
        });
        $(document).on('change', '.comCat', function() {
            var type = $(this).data('id');
            var val = $(this).val();
            var option = $('.com-' + type).find('option:selected').data('type');
            $('.comCat-' + type).val(option);
        });
        $(document).on('click', '.deleteRecord', function() {
            var dataid = $(this).data('id');
            $('.record-' + dataid).remove();
        });
        $(document).on('click', '.deleteCompanyRecord', function() {
            var payout = $(this).data('payout');
            var record_id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.admin.payout.record', '') }}" + "/" + payout,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Payout record has been deleted.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.record-' + record_id).remove();
                                    // location.reload();
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
