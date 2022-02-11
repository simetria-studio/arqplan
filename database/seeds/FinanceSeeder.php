<?php

use Illuminate\Database\Seeder;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\FinanceTransactionCategory;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Account
        $data = [
            [
                'company_id' => 1,
                'name' => 'Itaú 7154',
                'initial' => 46020.50,
            ],
            [
                'company_id' => 1,
                'name' => 'Bradesco',
                'initial' => 1245.19,
            ],
            [
                'company_id' => 2,
                'name' => 'Santander',
                'initial' => 3512.21,
            ],
        ];

        foreach ($data as $item) {
            FinanceAccount::updateOrInsert($item);
        }


        //Category        
        $data = [
            [
                'name' => 'Serviços',
            ],
            [
                'name' => 'Produtos',
            ],
            [
                'name' => 'Empréstimo',
            ],
            [
                'name' => 'Tarifa Bancária',
            ],
            [
                'name' => 'Comunicações',
            ],
            [
                'name' => 'Salários',
            ],
            [
                'name' => 'Aluguel',
            ],
            [
                'name' => 'Outros',
            ],
        ];

        foreach ($data as $item) {
            FinanceTransactionCategory::updateOrInsert($item);
        }


        //Transactions        
        $data = [
            [
                'description' => 'Aluguel salão',
                'date' => new DateTime(),
                'type' => 'D',
                'amount' => 1400.00,
                'financeaccount_id' => 1,
                'financecategory_id' => 1,
                'company_id' => 1,
            ],
            [
                'description' => 'Prestação caminhão',
                'date' => new DateTime(),
                'type' => 'D',
                'amount' => 890.55,
                'financeaccount_id' => 1,
                'financecategory_id' => 3,
                'company_id' => 1,
            ],
            [
                'description' => 'Pagamento Salários',
                'date' => new DateTime(),
                'type' => 'D',
                'amount' => 4500.00,
                'financeaccount_id' => 1,
                'financecategory_id' => 6,
                'company_id' => 1,
            ],
        ];

        foreach ($data as $item) {
            FinanceTransaction::updateOrInsert($item);
        }
    }
}
