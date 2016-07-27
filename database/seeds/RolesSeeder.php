<?php

use App\User;
use Bican\Roles\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();

        $adminRole = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
        ]);

        $admin_user = User::where('email', '=', 'admin@admin.com')->first();
        $admin_user->attachRole($adminRole);



        $shop = Role::create([
            'name' => 'Shop',
            'slug' => 'shop'
        ]);

        $shop_user = User::where('email', '=', 'shop@shop.com')->first();
        $shop_user->attachRole($shop);


    }
}
