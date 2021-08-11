<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'option_name' => 'site_title',
                'option_value' => 'Fx Crypto Assets',
            ),
            1 => 
            array (
                'id' => 2,
                'option_name' => 'contact_number',
                'option_value' => '+49 1111 2301998',
            ),
            2 => 
            array (
                'id' => 3,
                'option_name' => 'contact_email',
                'option_value' => 'support@fx-crypto-assets.com',
            ),
            3 => 
            array (
                'id' => 4,
                'option_name' => 'facebook',
                'option_value' => 'https://www.facebook.com/facebook',
            ),
            4 => 
            array (
                'id' => 5,
                'option_name' => 'twitter',
                'option_value' => 'https://www.twitter.com/twitter',
            ),
            5 => 
            array (
                'id' => 6,
                'option_name' => 'youtube',
                'option_value' => 'https://www.youtube.com',
            ),
            6 => 
            array (
                'id' => 7,
                'option_name' => 'user_deletion_days',
                'option_value' => '90',
            ),
            7 => 
            array (
                'id' => 8,
                'option_name' => 'wallet_address',
                'option_value' => '0xd733Dea83fFf749aEa99bbA541F6F1157A9Cb588',
            ),
        ));
        
        
    }
}