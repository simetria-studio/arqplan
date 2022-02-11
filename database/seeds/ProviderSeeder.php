<?php

use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
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
                'name' => 'Fornecedor 01',
                'description' => '',
                'cnpjcpf' => '',

                'email' => 'email@fornecedor1.com.br',
                'mobile' => '(11) 94778-6588',
                'phone' => '(11) 2659-6779',

                'zipcode' => '04243-000',
                'address' => 'Rua Amazonas',
                'addressnumber' => '433',
                'addresscomplement' => 'Apto 34',
                'neighborhood' => 'Centro',
                'city' => 'São Caetano do Sul',
                'state' => 'SP',
                'country' => 'Brazil',
                'company_id' => 1,
            ],
        ];

        foreach ($data as $item) {
            Provider::updateOrInsert($item);
        }
    }
}
