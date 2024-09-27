<div id="general_insurance_div" style="display: {{ $lead->invest_type !== "general insurance" ? 'none' :"" }};">
    <hr class="my-6">
    <h4>General Insurance</h4>
    <div id="fire_policy_div">
        @include('admin.lead.partials.create.insurance.fire_policy')
    </div>
</div>
