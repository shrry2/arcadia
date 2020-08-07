<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $initialSettings = [
            'system_name' => 'ARCADIA',
        ];

        foreach($initialSettings as $key => $value) {
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $value,
            ]);
        }
    }
}
