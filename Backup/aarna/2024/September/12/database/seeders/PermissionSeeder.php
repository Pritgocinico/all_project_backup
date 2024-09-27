<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserPermission;
use DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_permissions')->insert([
            [
                'user_id'       =>  1,
                'feature'       =>  'insurance_company',
                'capability'    =>  'company-view-global',
                'value'         =>  '1'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'insurance_company',
                'capability'    =>  'company-create-global',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'insurance_company',
                'capability'    =>  'company-edit-global',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'customer',
                'capability'    =>  'customer-global-view',
                'value'         =>  '1'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'customer',
                'capability'    =>  'customer-create',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'customer',
                'capability'    =>  'customer-edit',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'sourcing_agent',
                'capability'    =>  'agent-own-view',
                'value'         =>  '1'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'sourcing_agent',
                'capability'    =>  'agent-create',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'sourcing_agent',
                'capability'    =>  'agent-edit',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'health_plan',
                'capability'    =>  'plan-global-view',
                'value'         =>  '1'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'health_plan',
                'capability'    =>  'plan-create',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'health_plan',
                'capability'    =>  'plan-edit',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-own-view',
                'value'         =>  '1'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-create',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-edit',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'covernote',
                'capability'    =>  'covernote-delete',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'policy',
                'capability'    =>  'policy-own-view',
                'value'         =>  '1'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'policy',
                'capability'    =>  'policy-create',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'policy',
                'capability'    =>  'policy-edit',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'policy',
                'capability'    =>  'policy-delete',
                'value'         =>  '0'
            ],
            [
                'user_id'       =>  1,
                'feature'       =>  'cancel_policy',
                'capability'    =>  'cancel_policy-own-view',
                'value'         =>  '1'
            ],
        ]);
    }
}
