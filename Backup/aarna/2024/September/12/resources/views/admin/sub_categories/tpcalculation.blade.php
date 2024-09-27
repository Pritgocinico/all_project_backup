@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header card-no-border">
            <h4>Edit TP Calculation Parameter Premium | {{$category->name}}</h4>
        </div>
        <div class="card-body">
            @if(Session::has('alert'))
                <p class="alert
                {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
            @endif
            <form action="{{route('admin.update.parameters')}}" method="POST" class="row w-100" enctype="multipart/form-data">
                @csrf
                <div class="col-md-8">
                    <h4>Add Parameter</h4>
                    <label for="Parameters"><p>Select Parameters Type</p></label>
                    <div class="row">
                        <div class="col-md-10">
                            <select name="parameters" class="form-control" id="parameter">
                                <option value="0" selected hidden>Select Parameter</option>
                                <option value="1" @if(count($public_param) > 0) hidden @endif >Public Carrier</option>
                                <option value="2">Private Carrier</option>
                                <option value="3">Taxi</option>
                                <option value="4">Bus</option>
                                <option value="5">CC</option>
                                <option value="6">PA to Passanger</option>
                                <option value="7">Custom Field</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100 h-100 addParameter">+ Add New</button>
                        </div>
                    </div>
                </div>
                <div class="params-data">
                    <?php $p = 100; ?>
                    @if(!blank($public_param))
                        <div class="col-md-8 params-1">
                            <table class="w-100 border-0 publicCarrier">
                                @foreach ($public_param as $item)
                                        <?php $p++; ?>
                                        <tr class="border-0 pc-{{$p}}">
                                            <td class="border-0">Public Carrier</td>
                                            <td class="border-0"><input type="text" name="public[{{$p}}][carrier]" class="form-control" value="{{$item->carrier}}"></td>
                                            <td class="border-0 d-flex align-items-center">
                                                <input type="hidden" name="public[{{$p}}][id]" value="{{$item->id}}">
                                                <input type="number" class="m-3 form-control" name="public[{{$p}}][value]" id="" placeholder="" value="{{$item->carrier_value}}">
                                                @if ($loop->index >= 1)
                                                    <a href="javascript:void(0);" class="btn-denger delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                                @endif
                                            </td>
                                        </tr>
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addPublicCarrier me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="1" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                    @if(!blank($private_param))
                        <div class="col-md-8 params-2">
                            <table class="w-100 border-0 privateCarrier">
                                @foreach ($private_param as $item)
                                    <?php $p++; ?>
                                    <tr class="border-0 private-{{$p}}">
                                        <td class="border-0">Private Carrier</td>
                                        <td class="border-0"><input type="text" name="private[{{$p}}][carrier]" class="form-control" value="{{$item->carrier}}"></td>
                                        <td class="border-0 d-flex align-items-center">
                                            <input type="hidden" name="private[{{$p}}][id]" value="{{$item->id}}">
                                            <input type="text" class="m-3 form-control" name="private[{{$p}}][value]" id="" placeholder="" value="{{$item->carrier_value}}">
                                            @if ($loop->index >= 1)
                                                <a href="javascript:void(0);" class="delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addPrivateCarrier me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="2" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                    @if(!blank($taxi_param))
                        <div class="col-md-12 params-3">
                            <table class="w-100 border-0 TaxiParameter">
                                @foreach ($taxi_param as $taxi_id=>$item)
                                    @if($taxi_id != 0)
                                        <?php $p++; ?>
                                        <tr class="border-0 taxi-param-{{$p}}">
                                            <td class="border-0">TAXI</td>
                                            <td class="border-0 text-center">CC</td>
                                            <td class="border-0 d-flex"><input type="text" name="taxi[{{$p}}][taxi_cc]" class="m-3 form-control" value="{{$item->taxi_cc}}" placeholder="Custom field name"><input type="text" name="taxi[{{$p}}][taxi_cc_value]" class="m-3 form-control" value="{{$item->taxi_cc_value}}" placeholder="Custom field value"></td>
                                            <td class="border-0"><span class="m-5">Seating Capacity Rate</span></td>
                                            <td class="border-0 d-flex align-items-center">
                                                <input type="hidden" name="taxi[{{$p}}][id]" value="{{$item->id}}">
                                                <input type="text" class="m-3 form-control" name="taxi[{{$p}}][seating_capacity_rate]" id="" placeholder="" value="{{$item->seating_capacity_rate}}">
                                                <a href="javascript:void(0);" class="delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                            </td>
                                        </tr>
                                    @else
                                        <tr class="border-0">
                                            <td class="border-0">TAXI</td>
                                            <td class="border-0 text-center">Paid Driver</td>
                                            <td class="border-0"><input type="text" class="m-3 form-control" name="taxi[0][value]" id="" placeholder="" value="{{$item->taxi_value}}"><input type="hidden" name="taxi[0][id]" value="{{$item->id}}"></td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addTaxiParameter me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="3" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                    @if(!blank($bus_param))
                        <div class="col-md-8 params-4">
                            <table class="w-100 border-0 BusParameter">
                                @foreach ($bus_param as $item)
                                <?php $p++; ?>
                                    <tr class="border-0 bus-param-{{$p}}">
                                        <td class="border-0">BUS</td>
                                        <td class="border-0"><input type="text" name="bus[{{$p}}][label]" class="form-control" value="{{$item->label}}" placeholder="Custom field name"></td>
                                        <td class="border-0 d-flex align-items-center">
                                            <input type="hidden" name="bus[{{$p}}][id]" value="{{$item->id}}">
                                            <select name="bus[{{$p}}][display_type]" class="form-control ms-3" style="padding:6px 12px !important;" placeholder="Customer Field Type" >
                                                <option value="text" @if($item->display_type == 'text') selected @endif>Text</option>
                                                <option value="hidden_field" @if($item->display_type == 'hidden_field') selected @endif>Hidden Field</option>
                                                <option value="dropdown" @if($item->display_type == 'dropdown') selected @endif>Dropdown</option>
                                            </select>
                                            <input type="text" class="m-3 form-control" name="bus[{{$p}}][value]" id="" placeholder="" value="{{$item->carrier_value}}">
                                            @if ($loop->index >= 1)
                                            <a href="javascript:void(0);" class="delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addBusParameter me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="4" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                    @if(!blank($cc_param))
                        <div class="col-md-8 params-5">
                            <table class="w-100 border-0 CC">
                                @foreach ($cc_param as $item)
                                    <?php $p++; ?>
                                    <tr class="border-0 cc-{{$p}}">
                                        <td class="border-0">CC</td>
                                        <td class="border-0"><input type="text" name="cc[{{$p}}][label]" class="form-control" value="{{$item->label}}"></td>
                                        <td class="border-0 d-flex align-items-center">
                                            <input type="hidden" name="cc[{{$p}}][id]" value="{{$item->id}}">
                                            <input type="text" class="m-3 form-control" name="cc[{{$p}}][value]" id="" placeholder="" value="{{$item->carrier_value}}">
                                        </td>
                                        <td class="border-0">
                                            @if ($loop->index >= 1)
                                                <a href="javascript:void(0);" class="delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addCC me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="5" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                    @if(!blank($passanger_param))
                        <div class="col-md-8 params-6">
                            <table class="w-100 border-0 Passanger">
                                @foreach ($passanger_param as $item)
                                    <?php $p++; ?>
                                    <tr class="border-0 passanger-param-{{$p}}">
                                        <td class="border-0">PA to Passanger</td>
                                        <td class="border-0"><input type="text" name="passanger[{{$p}}][label]" class="form-control" value="{{$item->label}}"></td>
                                        <td class="border-0 d-flex align-items-center">
                                            <input type="hidden" name="passanger[{{$p}}][id]" value="{{$item->id}}">
                                            <input type="text" class="m-3 form-control" name="passanger[{{$p}}][value]" id="" placeholder="" value="{{$item->carrier_value}}">
                                            @if ($loop->index >= 1)
                                                <a href="javascript:void(0);" class="delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addPassanger me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="6" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                    @if(!blank($custom_param))
                        <div class="col-md-8 params-7">
                            <table class="w-100 border-0 customField">
                                @foreach ($custom_param as $item)
                                    <?php $p++; ?>
                                    <tr class="border-0 custom-param-{{$p}}">
                                        <td class="border-0"><input type="text" name="custom[{{$p}}][label]" class="form-control" value="{{$item->label}}" placeholder="Customer Field Name" ></td>
                                        <td class="border-0 d-flex align-items-center">
                                            <input type="hidden" name="custom[{{$p}}][id]" value="{{$item->id}}">
                                            <select name="custom[{{$p}}][display_type]" class="form-control ms-3" style="padding:6px 12px !important;" placeholder="Customer Field Type" >
                                                <option value="text" @if($item->display_type == 'text') selected @endif>Text</option>
                                                <option value="hidden_field" @if($item->display_type == 'hidden_field') selected @endif>Hidden Field</option>
                                                <option value="dropdown" @if($item->display_type == 'dropdown') selected @endif>Dropdown</option>
                                            </select>
                                            <input type="text" class="m-3 form-control" name="custom[{{$p}}][value]" id="" placeholder="" value="{{$item->carrier_value}}">
                                            @if ($loop->index >= 1)
                                                <a href="javascript:void(0);" class="delete-parameter" data-id="{{$item->id}}"><span class="text-danger">Delete</span></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <button type="button" class="btn btn-primary addCustomField me-2">Add Field</button><button type="button" class="btn btn-danger delete-all-params" data-id="7" data-cat="{{$category->id}}">Delete All</button>
                            <hr>
                        </div>
                    @endif
                </div>
                <div class="col-12 mt-3">
                    <input type="hidden" name="id" value="{{$category->id}}">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        var i = 0;
        $(document).on('click','.addParameter',function(){
            i++;
            var param = $('#parameter').val();
            var html = '';
            if(param == 1){
                html += '<div class="col-md-8 params-'+i+'">';
                html += '<table class="w-100 border-0 publicCarrier">';
                html += '<tr class="border-0 pc-1">';
                html += '<td class="border-0">Public Carrier</td>';
                html += '<td class="border-0"><input type="text" name="public[1][carrier]" class="form-control" value="7501 to 12000 KG"></td>';
                html += '<td class="border-0 d-flex align-items-center"><input type="number" class="m-3 form-control" name="public[1][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addPublicCarrier me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="'+i+'">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }else if(param == 2){
                html += '<div class="col-md-8 params-2">';
                html += '<table class="w-100 border-0 privateCarrier">';
                html += '<tr class="border-0">';
                html += '<td class="border-0">Private Carrier</td>';
                html += '<td class="border-0"><input type="text" name="private[1][carrier]" class="form-control" value="7501 to 12000 KG"></td>';
                html += '<td class="border-0 d-flex align-items-center"><input type="number" min="0" step="0.01" class="m-3 form-control" name="private[1][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addPrivateCarrier me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="2">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }else if(param == 3){
                html += '<div class="col-md-12 params-3">';
                html += '<table class="w-100 border-0 TaxiParameter">';
                html += '<tr class="border-0">';
                html += '<td class="border-0">TAXI</td>';
                html += '<td class="border-0">Paid Driver</td>';
                html += '<td class="border-0"><input type="text" class="m-3 form-control" name="taxi[0][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '<tr class="border-0 taxi-param-1">';
                html += '<td class="border-0">TAXI</td>';
                html += '<td class="border-0">CC</td>';
                html += '<td class="border-0 d-flex"><input type="text" name="taxi[1][taxi_cc]" class="m-3 form-control" value="" placeholder="Custom field name"><input type="text" name="taxi[1][taxi_cc_value]" class="m-3 form-control" value="" placeholder="Custom field Value"></td>';
                html += '<td class="border-0"><span class="m-5">Seating Capacity Rate</span></td>';
                html += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="taxi[1][seating_capacity_rate]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-taxi-param" data-id="1"><span class="text-danger">Delete</span></a></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addTaxiParameter me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="3">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }else if(param == 4){
                html += '<div class="col-md-8 params-4">';
                html += '<table class="w-100 border-0 BusParameter">';
                html += '<tr class="border-0">';
                html += '<td class="border-0">BUS</td>';
                html += '<td class="border-0"><input type="text" name="bus[1][label]" class="form-control" value="" placeholder="Custom field name"></td>';
                html += '<td class="border-0 d-flex align-items-center">';
                html += '<select name="bus[1][display_type]" class="form-control ms-3" style="padding:6px 12px !important;" placeholder="Customer Field Type">';
                html += '<option value="text">Text</option>';
                html += '<option value="hidden_field">Hidden Field</option>';
                html += '<option value="dropdown">Dropdown</option>';
                html += '</select>';
                html += '<input type="text" class="m-3 form-control" name="bus[1][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addBusParameter me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="4">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }else if(param == 5){
                html += '<div class="col-md-8 params-5">';
                html += '<table class="w-100 border-0 CC">';
                html += '<tr class="border-0">';
                html += '<td class="border-0">CC</td>';
                html += '<td class="border-0"><input type="text" name="cc[1][label]" class="form-control" value="7501 to 12000 KG"></td>';
                html += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="cc[1][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addCC me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="5">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }else if(param == 6){
                html += '<div class="col-md-8 params-6">';
                html += '<table class="w-100 border-0 Passanger">';
                html += '<tr class="border-0">';
                html += '<td class="border-0">PA to Passanger</td>';
                html += '<td class="border-0"><input type="text" name="passanger[1][label]" class="form-control" value="7501 to 12000 KG"></td>';
                html += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="passanger['+pa+'][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addPassanger me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="6">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }else if(param == 7){
                html += '<div class="col-md-8 params-7">';
                html += '<table class="w-100 border-0 customField">';
                html += '<tr class="border-0">';
                html += '<td class="border-0"><input type="text" name="custom[1][label]" class="form-control" value="" placeholder="Customer Field Name" ></td>';
                html += '<td class="border-0 d-flex align-items-center">';
                html += '<select name="custom[1][display_type]" class="form-control ms-3" style="padding:6px 12px !important;" placeholder="Customer Field Type">';
                html += '<option value="text">Text</option>';
                html += '<option value="hidden_field">Hidden Field</option>';
                html += '<option value="dropdown">Dropdown</option>';
                html += '</select>';
                html += '<input type="text" name="custom[1][type]" class="form-control ms-3" value="" placeholder="Customer Field Type" ><input type="text" class="m-3 form-control" name="custom[1][value]" id="" placeholder="" value="0.0"></td>';
                html += '</tr>';
                html += '</table>';
                html += '<button type="button" class="btn btn-primary addCustomField me-2">Add Field</button><button type="button" class="btn btn-danger delete-params" data-id="7">Delete All</button>'
                html += '<hr>';
                html += '</div>';
            }
            $('.params-data').append(html);
        });
        var p = 1;
        $(document).on('click','.addPublicCarrier',function(){
            p++;
            var public = '';
            public += '<tr class="border-0 pc-'+p+'">';
            public += '<td class="border-0">Public Carrier</td>';
            public += '<td class="border-0"><input type="text" name="public['+p+'][carrier]" class="form-control" value="7501 to 12000 KG"></td>';
            public += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="public['+p+'][value]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-pc" data-id="'+p+'"><span class="text-danger">Delete</span></a></td>';
            public += '</tr>';
            $('.publicCarrier').append(public);
        });
        var pr = 1;
        $(document).on('click','.addPrivateCarrier',function(){
            pr++;
            var public = '';
            public += '<tr class="border-0 private-'+pr+'">';
            public += '<td class="border-0">Private Carrier</td>';
            public += '<td class="border-0"><input type="text" name="private['+pr+'][carrier]" class="form-control" value="7501 to 12000 KG"></td>';
            public += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="private['+pr+'][value]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-private" data-id="'+pr+'"><span class="text-danger">Delete</span></a></td>';
            public += '</tr>';
            $('.privateCarrier').append(public);
        });
        var cc = 1;
        $(document).on('click','.addCC',function(){
            cc++;
            var public = '';
            public += '<tr class="border-0 cc-'+cc+'">';
            public += '<td class="border-0">CC</td>';
            public += '<td class="border-0"><input type="text" name="cc['+cc+'][label]" class="form-control" value="7501 to 12000 KG"></td>';
            public += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="cc['+cc+'][value]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-cc" data-id="'+cc+'"><span class="text-danger">Delete</span></a></td>';
            public += '</tr>';
            $('.CC').append(public);
        });
        var pa = 1;
        $(document).on('click','.addPassanger',function(){
            pa++;
            var public = '';
            public += '<tr class="border-0 passanger-param-'+pa+'">';
            public += '<td class="border-0">PA to Passanger</td>';
            public += '<td class="border-0"><input type="text" name="passanger['+pa+'][label]" class="form-control" value="7501 to 12000 KG"></td>';
            public += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="passanger['+pa+'][value]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-passanger-param" data-id="'+pa+'"><span class="text-danger">Delete</span></a></td>';
            public += '</tr>';
            $('.Passanger').append(public);
        });
        var b = 1;
        $(document).on('click','.addBusParameter',function(){
            b++;
            var param = '';
            param += '<tr class="border-0 bus-param-'+b+'">';
            param += '<td class="border-0">BUS</td>';
            param += '<td class="border-0"><input type="text" name="bus['+b+'][label]" class="form-control" value="" placeholder="Custom field name"></td>';
            param += '<td class="border-0 d-flex align-items-center">';
            param += '<select name="bus['+b+'][display_type]" class="form-control ms-3" style="padding:6px 12px !important;" placeholder="Customer Field Type">';
            param += '<option value="text">Text</option>';
            param += '<option value="hidden_field">Hidden Field</option>';
            param += '<option value="dropdown">Dropdown</option>';
            param += '</select>';
            param += '<input type="text" class="m-3 form-control" name="bus['+b+'][value]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-bus-param" data-id="'+b+'"><span class="text-danger">Delete</span></a></td>';
            param += '</tr>';
            $('.BusParameter').append(param);
        });
        var t = 1;
        $(document).on('click','.addTaxiParameter',function(){
            t++;
            var param = '';
            param += '<tr class="border-0 taxi-param-'+t+'">';
            param += '<td class="border-0">TAXI</td>';
            param += '<td class="border-0">CC</td>';
            param += '<td class="border-0 d-flex"><input type="text" name="taxi['+t+'][taxi_cc]" class="m-3 form-control" value="" placeholder="Custom field name"><input type="text" name="taxi['+t+'][taxi_cc_value]" class="m-3 form-control" value="" placeholder="Custom field value"></td>';
            param += '<td class="border-0"><span class="m-5">Seating Capacity Rate</span></td>';
            param += '<td class="border-0 d-flex align-items-center"><input type="text" class="m-3 form-control" name="taxi['+t+'][seating_capacity_rate]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-taxi-param" data-id="'+t+'"><span class="text-danger">Delete</span></a></td>';
            param += '</tr>';
            $('.TaxiParameter').append(param);
        });
        var cust = 1;
        $(document).on('click','.addCustomField',function(){
            cust++;
            var public = '';
            public += '<tr class="border-0 custom-param-'+cust+'">';
            public += '<td class="border-0"><input type="text" name="custom['+cust+'][label]" class="form-control" value="" placeholder="Customer Field Name" ></td>';
            public += '<td class="border-0 d-flex align-items-center">';
            public += '<select name="custom['+cust+'][display_type]" class="form-control ms-3" style="padding:6px 12px !important;" placeholder="Customer Field Type">';
            public += '<option value="text">Text</option>';
            public += '<option value="hidden_field">Hidden Field</option>';
            public += '<option value="dropdown">Dropdown</option>';
            public += '</select>';
            public += '<input type="text" class="m-3 form-control" name="custom['+cust+'][value]" id="" placeholder="" value="0.0"><a href="javascript:void(0);" class="delete-custom-param" data-id="'+cust+'"><span class="text-danger">Delete</span></a></td>';
            public += '</tr>';
            $('.customField').append(public);
        });
        $(document).on('click','.delete-pc',function(){
            var dataid = $(this).data('id');
            $('.pc-'+dataid).remove();
        });
        $(document).on('click','.delete-private',function(){
            var dataid = $(this).data('id');
            $('.private-'+dataid).remove();
        });
        $(document).on('click','.delete-params',function(){
            var par = $(this).data('id');
            $('.params-'+par).remove();
        });
        $(document).on('click','.delete-taxi-param',function(){
            var datataxi = $(this).data('id');
            $('.taxi-param-'+datataxi).remove();
        });
        $(document).on('click','.delete-bus-param',function(){
            var databus = $(this).data('id');
            $('.bus-param-'+databus).remove();
        });
        $(document).on('click','.delete-cc',function(){
            var datacc = $(this).data('id');
            $('.cc-'+datacc).remove();
        });
        $(document).on('click','.delete-custom-param',function(){
            var databus = $(this).data('id');
            $('.custom-param-'+databus).remove();
        });
        $(document).on('click','.delete-passanger-param',function(){
            var datapa = $(this).data('id');
            $('.passanger-param-'+datapa).remove();
        });
        $(document).on('click','.delete-parameter',function(){
            var param_id = $(this).attr('data-id');
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
                    url : "{{route('delete.parameter', '')}}"+"/"+param_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Parameter has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            }
            });
        });
        $(document).on('click','.delete-all-params',function(){
            var param_id = $(this).attr('data-id');
            var cat_id = $(this).attr('data-cat');
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
                    url : "{{route('delete.all.parameter')}}",
                    data : {'id':param_id,'cat_id': cat_id},
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Parameters has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            }
            });
        });
    </script>
@endsection
