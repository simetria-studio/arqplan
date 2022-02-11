<?php

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'ArqPlann',
                'description' => '',
                'cnpjcpf' => '10.570.560/0001-35',

                'zipcode' => '04243-000',
                'address' => 'Rua Amazonas',
                'addressnumber' => '433',
                'addresscomplement' => 'Apto 34',
                'neighborhood' => 'Centro',
                'city' => 'São Caetano do Sul',
                'state' => 'SP',
                'country' => 'Brazil',
                'approved' => true,
            ],
            [
                'name' => 'Company 2',
                'description' => '',
                'cnpjcpf' => '10.570.560/0001-35',

                'zipcode' => '04243-000',
                'address' => 'Rua Amazonas',
                'addressnumber' => '433',
                'addresscomplement' => 'Apto 34',
                'neighborhood' => 'Centro',
                'city' => 'São Caetano do Sul',
                'state' => 'SP',
                'country' => 'Brazil',
                'approved' => true,
            ],
        ];

        foreach ($data as $item) {
            Company::updateOrInsert($item);
        }
    }
}
