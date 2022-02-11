<?php

use Illuminate\Database\Seeder;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectStatus;
use App\Models\ProjectStep;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //PROJECT CATEGORIES
        $data = [
            [
                'name' => 'Residencial',
                'is_public' => true,
            ],
            [
                'name' => 'Corporativo',
                'is_public' => true,
            ],
            [
                'name' => 'Arquitetura',
                'is_public' => true,
            ],
            [
                'name' => 'Interiores',
                'is_public' => true,
            ],
            [
                'name' => 'Hospitalar',
                'is_public' => true,
            ],
            [
                'name' => 'Reforma',
                'is_public' => true,
            ],
            [
                'name' => 'Paisagismo',
                'is_public' => true,
            ],
        ];

        foreach ($data as $item) {
            ProjectCategory::updateOrInsert($item);
        }



        //PROJECT STATES
        $data = [
            [
                'name' => 'Novo',
            ],
            [
                'name' => 'Pausado',
            ],
            [
                'name' => 'Pendente',
            ],
            [
                'name' => 'Em andamento',
            ],
            [
                'name' => 'Cancelado',
            ],
            [
                'name' => 'Concluído',
            ],
        ];

        foreach ($data as $item) {
            ProjectStatus::updateOrInsert($item);
        }



        //PROJECT STEPS
        $data = [
            [
                'name' => 'Briefing',
            ],
            [
                'name' => 'Apresentação',
            ],
            [
                'name' => 'Ante projeto',
            ],
            [
                'name' => 'Estudo preliminar',
            ],
            [
                'name' => 'Projeto Executivo',
            ],
            [
                'name' => 'Decoração',
            ],
            [
                'name' => 'Entrega',
            ],
        ];

        foreach ($data as $item) {
            ProjectStep::updateOrInsert($item);
        }


        //PROJECTS
        $data = [
            [
                'startDate' => new DateTime(),
                'endDate' => new DateTime(),
                'code' => 10245,
                'name' => 'ArqPlann',
                'scope' => 'Projeto inicial da ArqPlann',
                'client_id' => 1,
                'company_id' => 1,
                'category_id' => 2
            ],
            [
                'startDate' => new DateTime(),
                'endDate' => new DateTime(),
                'code' => 10246,
                'name' => 'Projeto 2',
                'scope' => 'Projeto número 2!',
                'client_id' => 1,
                'company_id' => 1
            ],
            [
                'startDate' => new DateTime(),
                'endDate' => new DateTime(),
                'code' => 10247,
                'name' => 'Projeto 3',
                'scope' => 'Projeto 3',
                'client_id' => 2,
                'company_id' => 1
            ],
        ];

        foreach ($data as $item) {
            Project::updateOrInsert($item);
        }
    }
}
