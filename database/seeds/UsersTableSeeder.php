<?php

use App\Models\Tenant\Tenant;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Rob',
            'last_name'  => 'Burley',
            'username'   => 'robburley',
            'email'      => 'rcburley@icloud.com',
            'password'   => 'password',
            'tenant_id'  => Tenant::where('domain', 'quincomm')->first()->id,
        ]);

        $user->roles()->attach([1]);

        $user = User::create([
            'first_name' => 'Dave',
            'last_name'  => 'Duckworth',
            'username'   => 'dd',
            'email'      => 'dave.duckworth@me.com',
            'password'   => 'password',
            'tenant_id'  => Tenant::where('domain', 'quincomm')->first()->id,
        ]);

        $user->roles()->attach([1]);
    }
}
