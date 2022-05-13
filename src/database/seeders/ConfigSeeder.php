<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->seedSMTP();
       $this->seedGoogleApi();
    }


    private function seedGoogleApi() {
        Config::create([
            'name' => 'google_app_recaptcha2_site_key',
            'value' => '6Lczu1caAAAAAPQeZL6WDcUKQlGNZrhT1b_qD3MI',
        ]);
    }


    private function seedSMTP() {
        Config::create([
            'name' => 'smtp_host',
            'value' => 'serwer2000157.home.pl',
        ]);
        Config::create([
            'name' => 'smtp_port',
            'value' => '465',
        ]);
        Config::create([
            'name' => 'smtp_username',
            'value' => 'tomsolution_noreply@rescodev.pl',
        ]);
        Config::create([
            'name' => 'smtp_password',
            'value' => 'Sk5cMPXTu',
        ]);
        Config::create([
            'name' => 'smtp_encryption',
            'value' => 'SSL',
        ]);
        Config::create([
            'name' => 'smtp_from_address',
            'value' => 'tomsolution_noreply@rescodev.pl',
        ]);
        Config::create([
            'name' => 'smtp_from_name',
            'value' => 'Tomsolution CMS',
        ]);
    }
}
