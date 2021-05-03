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
                'name' => 'investors-list',
                'status' => 1,
            ),
            1 => 
            array (
                'module_id' => 1,
                'name' => 'investors-create',
                'status' => 1,
            ),
            2 => 
            array (
                'module_id' => 1,
                'name' => 'investors-edit',
                'status' => 1,
            ),
            3 => 
            array (
                'module_id' => 1,
                'name' => 'investors-delete',
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
                'name' => 'investors-view',
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
        ));
        
        
    }
}