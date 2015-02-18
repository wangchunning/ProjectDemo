<?php

class RoleSeeder extends Seeder {

    public function run()
    {
        $roles = array(
            'Administrator', 
            'Manager', 
            'Account Receiable', 
            'Account Payable', 
            'Account Manager', 
            'Level 2 Customer Service', 
            'Level 1 Customer Service'
        );

        foreach ($roles as $role)
        {
            $bot = new Role;
            
            $bot->name = $role;
            $bot->description = 'Manage Wexchange system information and user accounts';
            $bot->save();
        }
    }
}