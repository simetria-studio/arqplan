<?php

use Illuminate\Database\Seeder;
use App\Models\UserProfile;

class UserProfileSeeder extends Seeder
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
                'code' => 'ADMIN',
                'name' => 'Administrador',
            ],
            [
                'code' => 'PROJECT_VIEW',
                'name' => 'Visualizar projetos',
            ],
            [
                'code' => 'PROJECT_EDIT',
                'name' => 'Editar projetos',
            ],
            [
                'code' => 'CLIENT_VIEW',
                'name' => 'Visualizar clientes',
            ],
            [
                'code' => 'CLIENT_EDIT',
                'name' => 'Editar clientes',
            ],
            [
                'code' => 'PROVIDER_VIEW',
                'name' => 'Visualizar fornecedores',
            ],
            [
                'code' => 'PROVIDER_EDIT',
                'name' => 'Editar fornecedores',
            ],
            [
                'code' => 'FINANCE_VIEW',
                'name' => 'Visualizar financeiro',
            ],
            [
                'code' => 'FINANCE_EDIT',
                'name' => 'Editar financeiro',
            ],
            [
                'code' => 'REPORT_VIEW',
                'name' => 'Visualizar relatórios',
            ],
            [
                'code' => 'USER_EDIT',
                'name' => 'Editar usuários',
            ],
        ];

        foreach ($data as $item) {
            UserProfile::updateOrInsert($item);
        }
    }
}
