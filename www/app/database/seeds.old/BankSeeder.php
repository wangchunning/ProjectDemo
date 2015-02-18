<?php

class BankSeeder extends Seeder {

    public function run()
    {

        $banks = array(

                    array(
                            'bank'          => 'Westpac Bank',
                            'branch'        => 'melbourne branch',
                            'street_number' => '360',
                            'street'        => 'COLLINS STREET',
                            'city'          => 'Melbourne',
                            'state'         => 'VIC',
                            'postcode'      => '3000',
                            'account'       => 'XINYING PTY LTD',
                            'account_number'=> '460104',
                            'as_customer_num' => 0,
                            'BSB'           => '03 3000',
                            'currency'      =>'AUD',
                            'status'        => 1,

                        ),
                    array(
                            'bank'          => 'Westpac Banking Corporation',
                            'branch'        => 'melbourne branch',
                            'street_number' => '360',
                            'street'        => 'COLLINS STREET',
                            'city'          => 'Melbourne',
                            'state'         => 'VIC',
                            'postcode'      => '3000',
                            'account'       => 'XINYING PTY LTD',
                            'account_number'=> '353156',
                            'as_customer_num' => 0,
                            'BSB'           => '03 4702',
                            'currency'      => 'USD',
                            'status'        => 1,
                        ),
                    array(
                            'bank'          => 'Westpac Banking Corporation',
                            'branch'        => 'melbourne branch',
                            'street_number' => '360',
                            'street'        => 'COLLINS STREET',
                            'city'          => 'Melbourne',
                            'state'         => 'VIC',
                            'postcode'      => '3000',
                            'account'       => 'XINYING PTY LTD',
                            'account_number'=> '310452',
                            'as_customer_num' => 0,
                            'BSB'           => '03 4714',
                            'currency'      => 'CAD',
                            'status'        => 1,
                        ),
                    array(
                            'bank'          => 'Westpac Banking Corporation',
                            'branch'        => 'melbourne branch',
                            'street_number' => '360',
                            'street'        => 'COLLINS STREET',
                            'city'          => 'Melbourne',
                            'state'         => 'VIC',
                            'postcode'      => '3000',
                            'account'       => 'XINYING PTY LTD',
                            'account_number'=> '460104',
                            'as_customer_num' => 0,
                            'BSB'           => '03 3000',
                            'currency'      => 'HKD',
                            'status'        => 1,
                        ),

            );

        foreach ($banks as $bank)
        {
            $bk = new Bank;

            foreach($bank as $key => $value)
            {
                $bk->$key = $value;     
            }

            $bk->save();
        }
    }
}
