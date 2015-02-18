<?php

class UserSeeder extends Seeder {

    public function run()
    {
        // 管理员账号
        $user = new User;
        $user->type = 'manager';
        $user->email = 'admin@wexchange.com';
        $user->password = Hash::make('123456');
        $user->first_name = 'Zo';
        $user->last_name = 'Liang';
        $user->save();      
        $user->roles()->attach(1);

        // 用户账号
        $user = new User;
        $profile = new Profile;

        $user->type = 'user';
        $user->email = 'demo@wexchange.com';
        $user->password = Hash::make('123456');
        $user->first_name = 'Jack';
        $user->last_name = 'Chen';
        $user->save();

        $profile->birth_day = '12';
        $profile->birth_month  = '6';
        $profile->birth_year = '1981';
        $profile->phone_number = '0433880888';
        $profile->unit_number = 'Suite 5 Level 3';
        $profile->street_number = '450';
        $profile->street = 'St Klida RD';
        $profile->street_type = 'St';
        $profile->city = 'Melbourne';
        $profile->state = 'VIC';
        $profile->postcode = '3004';    
        $user->profile()->save($profile);

        $user->verify->email_verified = 1;
        $user->verify->save();

        $balance = new Balance;
        $balance->currency = 'AUD';
        $balance->amount = '0';
        $user->balances()->save($balance);
    }
}