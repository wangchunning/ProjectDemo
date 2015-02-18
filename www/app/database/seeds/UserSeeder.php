<?php

class UserSeeder extends Seeder {

    public function run()
    {
        // 管理员账号
        $user = new Administrator;
        
        $user->parent_uid = 0;
        $user->has_reset_password = 1;
        $user->user_name = 'admin';
        $user->password = Hash::make('213456');
        $user->real_name = 'Admin';
        $user->status = 1;
        $user->save();      

        
        // 用户账号
        $user = new User;
        $user->user_name = 'demo';
        $user->user_level = 0;
        $user->login_password = Hash::make('123456');

        $user->status = 1;
        $user->save();

    }
}