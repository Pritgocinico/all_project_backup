<div id="general_insurance_div" style="display: @if ($type !== 2) none @endif;">
    <hr class="my-6">
    <h4>General Insurance</h4>
    <div id="fire_policy_div" class="d-none">
        @include('admin.lead.partials.create.insurance.fire_policy')
    </div>
    <div id="wc_policy_div" class="d-none">
        @include('admin.lead.partials.create.insurance.wc_policy')
    </div>
    <div id="health_policy_div" class="d-none">
        @include('admin.lead.partials.create.insurance.health_policy')
    </div>
    <div id="pa_policy_div" class="d-none">
        @include('admin.lead.partials.create.insurance.pa_policy')
    </div>
</div>
