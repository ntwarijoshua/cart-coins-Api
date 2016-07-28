<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->delete();
        App\Company::Create([
            'name'          => 'XXX ltd',
            'vat'           =>'123',
            'pobox'         => '5826',
            'zip_code'      => '250',
            'city'          => 'Kigali',
            'country'       => 'Rwanda',
            'phone'         => '+250788355919',
            'website'       => '',
            'user_id'       => '1',
            'manager_id'    => '2',
            'category_id'   => '1',
        ]);

        App\Company::Create([
            'name'          => 'YYY ltd',
            'vat'           =>'456',
            'pobox'         => '5827',
            'zip_code'      => '250',
            'city'          => 'Kigali',
            'country'       => 'Rwanda',
            'phone'         => '+250786160780',
            'website'       => '',
            'user_id'       => '1',
            'manager_id'    => '2',
            'category_id'   => '1',
        ]);
    }
}
