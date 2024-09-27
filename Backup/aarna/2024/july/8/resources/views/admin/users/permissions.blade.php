@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header justify-content-between d-flex card-no-border">
                    <h4>Name : {{ $user->name }}</h4>
                  </div>
        <div class="card-body table-responsive">
            <form action="{{ route('update.permission') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Features</th>
                            <th>Capability</th>  
                        </tr>
                    </thead>
                    <tbody>
                            <?php 
                                $arr=array();
                                if(count($user_permissions) > 0){
                                    foreach($user_permissions as $permission){
                                        $arr[$permission->capability]=$permission->value;
                                        // echo $permission->capability.'-'.$permission->value.'<br>';
                                    }
                                }
                            ?>
                            <tr>
                                <td>Insuarance Company</td>
                                <td>
                                    <input type="hidden" name="permission[insurance_company][company-view-global]" value="0">
                                    <input type="checkbox" name="permission[insurance_company][company-view-global]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['company-view-global']) && $arr['company-view-global'] == 1){ ?>
                                                checked
                                        <?php }
                                    } ?> 
                                    id=""> View (Global)<br>
                                    <input type="hidden" name="permission[insurance_company][company-create]" value="0" id="">
                                    <input type="checkbox" name="permission[insurance_company][company-create]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['company-create']) && $arr['company-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[insurance_company][company-edit]" value="0" id="">
                                    <input type="checkbox" name="permission[insurance_company][company-edit]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['company-edit']) && $arr['company-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit
                                </td>
                            </tr>
                            <tr>
                                <td>Customers</td>
                                <td>
                                    <input type="hidden" name="permission[customer][customer-global-view]" value="0" id="">
                                    <input type="checkbox" name="permission[customer][customer-global-view]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['customer-global-view']) && $arr['customer-global-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View (Global)<br>
                                    <input type="hidden" name="permission[customer][customer-create]" value="0" id="">
                                    <input type="checkbox" name="permission[customer][customer-create]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['customer-create']) && $arr['customer-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[customer][customer-edit]" value="0" id="">
                                    <input type="checkbox" name="permission[customer][customer-edit]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['customer-edit']) && $arr['customer-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit
                                </td>
                            </tr>
                            <tr>
                                <td>Sourcing Agents</td>
                                <td>
                                    <input type="hidden" name="permission[sourcing_agent][agent-own-view]" value="0" id="">
                                    <input type="checkbox" name="permission[sourcing_agent][agent-own-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['agent-own-view']) && $arr['agent-own-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View (Global)<br>
                                    <input type="hidden" name="permission[sourcing_agent][agent-create]" value="0" id="">
                                    <input type="checkbox" name="permission[sourcing_agent][agent-create]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['agent-create']) && $arr['agent-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[sourcing_agent][agent-edit]" value="0" id="">
                                    <input type="checkbox" name="permission[sourcing_agent][agent-edit]"
                                    <?php if(!empty($arr)){
                                            if(isset($arr['agent-edit']) && $arr['agent-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Health Plan</td>
                                <td>
                                    <input type="hidden" name="permission[health_plan][plan-own-view]" value="0" id="">
                                    <input type="checkbox" name="permission[health_plan][plan-own-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['plan-own-view']) && $arr['plan-own-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View<br>
                                    <input type="hidden" name="permission[health_plan][plan-create]" value="0" id="">
                                    <input type="checkbox" name="permission[health_plan][plan-create]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['plan-create']) && $arr['plan-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[health_plan][plan-edit]" value="0" id="">
                                    <input type="checkbox" name="permission[health_plan ][plan-edit]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['plan-edit']) && $arr['plan-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Business Source</td>
                                <td>
                                    <input type="hidden" name="permission[business_source][source-global-view]" value="0" id="">
                                    <input type="checkbox" name="permission[business_source][source-global-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['source-global-view']) && $arr['source-global-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View (Global)<br>
                                    <input type="hidden" name="permission[business_source][source-create]" value="0" id="">
                                    <input type="checkbox" name="permission[business_source][source-create]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['source-create']) && $arr['source-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[business_source][source-edit]" value="0" id="">
                                    <input type="checkbox" name="permission[business_source][source-edit]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['source-edit']) && $arr['source-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit<br>
                                    <input type="hidden" name="permission[business_source][source-delete]" value="0" id="">
                                    <input type="checkbox" name="permission[business_source][source-delete]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['source-delete']) && $arr['source-delete'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Delete
                                </td>
                            </tr>
                            <tr>
                                <td>Covernote</td>
                                <td>
                                    <input type="hidden" name="permission[covernote][covernote-own-view]" value="0" id="">
                                    <input type="checkbox" name="permission[covernote][covernote-own-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['covernote-own-view']) && $arr['covernote-own-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View (Own)<br>
                                    <input type="hidden" name="permission[covernote][covernote-create]" value="0" id="">
                                    <input type="checkbox" name="permission[covernote][covernote-create]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['covernote-create']) && $arr['covernote-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[covernote][covernote-edit]" value="0" id="">
                                    <input type="checkbox" name="permission[covernote][covernote-edit]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['covernote-edit']) && $arr['covernote-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit<br>
                                    <input type="hidden" name="permission[covernote][covernote-convert]" value="0" id="">
                                    <input type="checkbox" name="permission[covernote][covernote-convert]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['covernote-convert']) && $arr['covernote-convert'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Convert to Policy<br>
                                    <input type="hidden" name="permission[covernote][covernote-delete]" value="0" id="">
                                    <input type="checkbox" name="permission[covernote][covernote-delete]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['covernote-delete']) && $arr['covernote-delete'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Delete
                                </td>
                            </tr>
                            <tr>
                                <td>Policy</td>
                                <td>
                                    <input type="hidden"  name="permission[policy][policy-own-view]" value="0" >
                                    <input type="checkbox" name="permission[policy][policy-own-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-own-view']) && $arr['policy-own-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View<br>
                                    <input type="hidden"  name="permission[policy][policy-create]" value="0" >
                                    <input type="checkbox" name="permission[policy][policy-create]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-create']) && $arr['policy-create'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Create<br>
                                    <input type="hidden" name="permission[policy][policy-edit]" value="0">
                                    <input type="checkbox" name="permission[policy][policy-edit]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-edit']) && $arr['policy-edit'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Edit<br>
                                    <input type="hidden" name="permission[policy][policy-endorsement]" value="0">
                                    <input type="checkbox" name="permission[policy][policy-endorsement]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-endorsement']) && $arr['policy-endorsement'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Endorsement<br>
                                    <input type="hidden" name="permission[policy][policy-claims]" value="0">
                                    <input type="checkbox" name="permission[policy][policy-claims]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-claims']) && $arr['policy-claims'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Claims<br>
                                    <input type="hidden" name="permission[policy][policy-renew]" value="0">
                                    <input type="checkbox" name="permission[policy][policy-renew]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-renew']) && $arr['policy-renew'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Renew Policy<br>
                                    <input type="hidden" name="permission[policy][renew-to-covernote]" value="0">
                                    <input type="checkbox" name="permission[policy][renew-to-covernote]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['renew-to-covernote']) && $arr['renew-to-covernote'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Renew Policy to Covernote <br>
                                    <input type="hidden" name="permission[policy][policy-delete]" value="0">
                                    <input type="checkbox" name="permission[policy][policy-delete]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['policy-delete']) && $arr['policy-delete'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Delete <br>
                                     <input type="hidden" name="permission[policy][policy-cancel]" value="0">
                                     <input type="checkbox" name="permission[policy][policy-cancel]" 
                                     <?php if(!empty($arr)){
                                             if(isset($arr['policy-cancel']) && $arr['policy-cancel'] == 1){ ?>
                                                 checked 
                                         <?php }
                                     } ?>> Cancel Policy<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Cancel Policy</td>
                                <td>
                                    <input type="hidden" name="permission[cancel_policy][cancel_policy-own-view]" value="0" id="">
                                    <input type="checkbox" name="permission[cancel_policy][cancel_policy-own-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['cancel_policy-own-view']) && $arr['cancel_policy-own-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> View (own)<br>
                                </td>
                            </tr>
                            <tr>
                                <td>Reports</td>
                                <td>
                                    <input type="hidden" name="permission[reports][reports-view]" value="0" id="">
                                    <input type="checkbox" name="permission[reports][reports-view]" 
                                    <?php if(!empty($arr)){
                                            if(isset($arr['reports-view']) && $arr['reports-view'] == 1){ ?>
                                                checked 
                                        <?php }
                                    } ?>> Reports (Global)<br>
                                </td>
                            </tr>
                    </tbody>
                </table>
                <div>
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="btn btn-primary justify-content-end float-right mt-2">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
