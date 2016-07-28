<?php

use Illuminate\Database\Seeder;

class CompanyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_categories')->delete();
        App\CompanyCategory::Create([
            'category_name'      => 'AIO',
            'description'     =>'all in one'
        ]);

        App\CompanyCategory::Create([
            'category_name'      => 'Hotel',
            'description'     =>'hotel'
        ]);

        App\CompanyCategory::Create([
            'category_name'      => 'Supermarket',
            'description'     =>'supermarket'
        ]);

        App\CompanyCategory::Create([
            'category_name'      => 'Transport',
            'description'     =>'transport'
        ]);
    }
}
