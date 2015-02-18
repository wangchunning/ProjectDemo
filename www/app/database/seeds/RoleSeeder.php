<?php

class RoleSeeder extends Seeder {

    public function run()
    {

		$bot = new Role;
        $bot->name = 'Administrator';
        //$bot->description = '系统超级管理员';
        $bot->save();
        
        $user = Administrator::where('user_name', 'admin')->first();
        $user->roles()->attach( $bot->id );
        
    }
}