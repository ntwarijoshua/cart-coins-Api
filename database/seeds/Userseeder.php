<?php

use Illuminate\Database\Seeder;

class Userseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        App\User::Create([
            'name'      => 'admin',
            'email'     =>'admin@admin.com',
            'password'  =>Hash::make('admin')
        ]);

        App\User::create([
            'name'      => 'shop',
            'email'     =>'shop@shop.com',
            'password'  =>Hash::make('shop')
        ]);

        App\User::create([
            'name'      => 'client',
            'email'     =>'client@client.com',
            'password'  =>Hash::make('client')
        ]);
    }
}
