<?php

use App\Models\Users\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'         => 'Administrator',
            'display_name' => 'Administrator',
        ]);

        Role::create([
            'name'         => 'Data Controller',
            'display_name' => 'Data Controller',
        ]);

        Role::create([
            'name'         => 'User',
            'display_name' => 'User',
        ]);
    }
}
