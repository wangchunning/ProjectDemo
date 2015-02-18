<?php

class BasicRateSeeder extends Seeder {

    public function run()
    {
        // 
        $br = new BasicRate;
        $br->day            = date('Y-m-d');
        $br->currency       = 'USDCNY';
        $br->rate           = '6.1700';
        $br->operator_name  = 'Admin';
        $br->operator_id    = '1';
        $br->save();      

    }
}
