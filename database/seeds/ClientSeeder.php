<?php

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
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
                'name' => 'Client 01',
                'description' => '',
                'cnpjcpf' => '',

                'email' => 'email@client1.com.br',
                'mobile' => '(11) 96545-1245',
                'phone' => '(11) 2456-6588',

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
            Client::updateOrInsert($item);
        }
    }
}
