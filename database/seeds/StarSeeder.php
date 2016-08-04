<?php

use Illuminate\Database\Seeder;

class StarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stars')->delete();
        App\Star::Create([
            'name'          => '1',
            'details'       =>'one star',
        ]);

        App\Star::Create([
            'name'          => '2',
            'details'       =>'two star',
        ]);

        App\Star::Create([
            'name'          => '3',
            'details'       =>'three star',
        ]);

        App\Star::Create([
            'name'          => '4',
            'details'       =>'four star',
        ]);

        App\Star::Create([
            'name'          => '5',
            'details'       =>'five star',
        ]);
    }
}
