<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $user = new User;
            $user->name = 'Super';
            $user->last_name = 'Admin';
            $user->password = bcrypt('secret');
            $user->email = 'admin@admin.com';
            $user->admin = true;
            $user->save();
    	}catch(Exception $e){

    	}
    }
}
