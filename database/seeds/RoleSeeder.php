<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(count(Role::all()) == 0)
        {
            Role::create([
                'name' => 'Administrador'
            ]);

            Role::create([
                'name' => 'General'
            ]);
        }
    }
}
