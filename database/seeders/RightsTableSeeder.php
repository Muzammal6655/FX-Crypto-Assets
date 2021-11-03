<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('rights')->delete();
        
        \DB::table('rights')->insert(array (
            0 => 
            array (
                'module_id' => 1,
                'name' => 'customers-list',
                'status' => 1,
            ),
            1 => 
            array (
                'module_id' => 1,
                'name' => 'customers-create',
                'status' => 1,
            ),
            2 => 
            array (
                'module_id' => 1,
                'name' => 'customers-edit',
                'status' => 1,
            ),
            3 => 
            array (
                'module_id' => 1,
                'name' => 'customers-delete',
                'status' => 1,
            ),
            4 => 
            array (
                'module_id' => 2,
                'name' => 'roles-list',
                'status' => 1,
            ),
            5 => 
            array (
                'module_id' => 2,
                'name' => 'roles-create',
                'status' => 1,
            ),
            6 => 
            array (
                'module_id' => 2,
                'name' => 'roles-edit',
                'status' => 1,
            ),
            7 => 
            array (
                'module_id' => 2,
                'name' => 'roles-delete',
                'status' => 1,
            ),
            8 => 
            array (
                'module_id' => 3,
                'name' => 'admins-list',
                'status' => 1,
            ),
            9 => 
            array (
                'module_id' => 3,
                'name' => 'admins-create',
                'status' => 1,
            ),
            10 => 
            array (
                'module_id' => 3,
                'name' => 'admins-edit',
                'status' => 1,
            ),
            11 => 
            array (
                'module_id' => 3,
                'name' => 'admins-delete',
                'status' => 1,
            ),
            12 => 
            array (
                'module_id' => 4,
                'name' => 'email-templates-list',
                'status' => 1,
            ),
            13 => 
            array (
                'module_id' => 4,
                'name' => 'email-templates-create',
                'status' => 1,
            ),
            14 => 
            array (
                'module_id' => 4,
                'name' => 'email-templates-edit',
                'status' => 1,
            ),
            15 => 
            array (
                'module_id' => 4,
                'name' => 'email-templates-delete',
                'status' => 1,
            ),
            16 => 
            array (
                'module_id' => 5,
                'name' => 'site-settings',
                'status' => 1,
            ),
            17 => 
            array (
                'module_id' => 1,
                'name' => 'customers-view',
                'status' => 1,
            ),
            18 => 
            array (
                'module_id' => 6,
                'name' => 'pools-list',
                'status' => 1,
            ),
            19 => 
            array (
                'module_id' => 6,
                'name' => 'pools-create',
                'status' => 1,
            ),
            20 => 
            array (
                'module_id' => 6,
                'name' => 'pools-edit',
                'status' => 1,
            ),
            21 => 
            array (
                'module_id' => 6,
                'name' => 'pools-delete',
                'status' => 1,
            ),
            22 => 
            array (
                'module_id' => 1,
                'name' => 'customers-referrals',
                'status' => 1,
            ),
            23 => 
            array (
                'module_id' => 1,
                'name' => 'customers-transactions',
                'status' => 1,
            ),
            24 => 
            array (
                'module_id' => 1,
                'name' => 'customers-balances',
                'status' => 1,
            ),
            25 => 
            array (
                'module_id' => 1,
                'name' => 'customers-kyc',
                'status' => 1,
            ),
            26 => 
            array (
                'module_id' => 7,
                'name' => 'pool-investments-list',
                'status' => 1,
            ),
            27 => 
            array (
                'module_id' => 7,
                'name' => 'pool-investments-view',
                'status' => 1,
            ),
            28 => 
            array (
                'module_id' => 7,
                'name' => 'pool-investments-approve',
                'status' => 1,
            ),
            29 => 
            array (
                'module_id' => 7,
                'name' => 'pool-investments-reject',
                'status' => 1,
            ),
            30 => 
            array (
                'module_id' => 8,
                'name' => 'deposits-list',
                'status' => 1,
            ),
            31 => 
            array (
                'module_id' => 8,
                'name' => 'deposits-view',
                'status' => 1,
            ),
            32 => 
            array (
                'module_id' => 8,
                'name' => 'deposits-approve',
                'status' => 1,
            ),
            33 => 
            array (
                'module_id' => 8,
                'name' => 'deposits-reject',
                'status' => 1,
            ),
            34 => 
            array (
                'module_id' => 9,
                'name' => 'withdraws-list',
                'status' => 1,
            ),
            35 => 
            array (
                'module_id' => 9,
                'name' => 'withdraws-view',
                'status' => 1,
            ),
            36 => 
            array (
                'module_id' => 9,
                'name' => 'withdraws-approve',
                'status' => 1,
            ),
            37 => 
            array (
                'module_id' => 9,
                'name' => 'withdraws-reject',
                'status' => 1,
            ),
            38 => 
            array (
                'module_id' => 10,
                'name' => 'profits-list',
                'status' => 1,
            ),
            39 => 
            array (
                'module_id' => 10,
                'name' => 'profits-import',
                'status' => 1,
            ),
            40 => 
            array (
                'module_id' => 11,
                'name' => 'pool-balances-list',
                'status' => 1,
            ),
            41 => 
            array (
                'module_id' => 11,
                'name' => 'pool-balances-import',
                'status' => 1,
            ),
            42 => 
            array (
                'module_id' => 1,
                'name' => 'kyc-document-history',
                'status' => 1,
            ),
            43 => 
            array (
                'module_id' => 1,
                'name' => 'document-history-view',
                'status' => 1,
            ),
        ));
        
        
    }
} 