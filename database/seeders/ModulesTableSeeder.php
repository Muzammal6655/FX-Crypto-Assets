<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('modules')->delete();
        
        \DB::table('modules')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Customers',
                'status' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Roles',
                'status' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Sub-Admins',
                'status' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Email Templates',
                'status' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Site Settings',
                'status' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Pools',
                'status' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Pool Investments',
                'status' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Deposits',
                'status' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Withdraws',
                'status' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Profits',
                'status' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Pool Balances',
                'status' => 1,
            ),
        ));
    }
}