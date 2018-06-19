<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleList = ['admin', 'super_admin'];
        $adminUser = App\User::create([
            'uuid' => \Uuid::generate(),
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jd@g.c',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'is_activated' => 0,
            'remember_token' => str_random(10)
        ]);

        $superAdminUser = App\User::create([
            'uuid' => \Uuid::generate(),
            'first_name' => 'Jhon',
            'last_name' => 'Doe',
            'email' => 'johnd@g.c',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'is_activated' => 1,
            'remember_token' => str_random(10)
        ]);
        $admin = new \App\Role();
        $admin->name = $roleList[0];
        $admin->save();


        $superAdmin = new \App\Role();
        $superAdmin->name = $roleList[1];
        $superAdmin->save();

        $adminUser->attachRole($admin);
        $superAdminUser->attachRole($superAdmin);

        factory(User::class, 5)->create();
    }
}
