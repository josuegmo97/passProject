<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(count(User::all()) == 0)
        {
            User::create([
                'username' => 'Administrador',
                'email'    => 'admin@admin.com',
                'password' => bcrypt('123456789.'),
                'role_id'  => Role::ADMIN
            ]);
        }
    }
}
