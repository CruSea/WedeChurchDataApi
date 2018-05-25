<?php

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
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        DB::table('roles')->delete();

        $roles = array(
            ['name' => 'admin'],
            ['name' => 'datacollector']
        );

        // Loop through each user above and create the record for them in the database
        foreach ($roles as $role)
        {
            Role::create($role);
        }
        $role = Role::where('name', '=', 'admin');
        $user = User::where('email', '=', 'chris@scotch.io')->first();
        $user->roles()->attach($role->id);
        Model::reguard();
    }
    }
}
