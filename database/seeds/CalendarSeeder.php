<?php

use Illuminate\Database\Seeder;
use App\Models\Event;

class CalendarSeeder extends Seeder
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
                'company_id' => 1,
                'user_id' => 2,
                'start' => new DateTime('29-02-2021 12:00'),
                'end' => new DateTime('29-02-2021 16:00'),
                'title' => 'Evento 01',
            ],
            [
                'company_id' => 1,
                'user_id' => 2,
                'start' => new DateTime('02-02-2021 09:30'),
                'end' => new DateTime('02-02-2021 12:30'),
                'title' => 'Evento 02',
            ],
            [
                'company_id' => 1,
                'user_id' => 2,
                'start' => new DateTime('07-02-2021 09:30'),
                'end' => new DateTime('07-02-2021 12:30'),
                'title' => 'Evento 03',
            ],
            [
                'company_id' => 1,
                'user_id' => 3,
                'start' => new DateTime('04-02-2021 10:30'),
                'end' => new DateTime('04-02-2021 17:30'),
                'title' => 'Evento privado',
                'is_public' => false,
            ],
            [
                'company_id' => 1,
                'user_id' => 2,
                'start' => new DateTime('04-02-2021 12:30'),
                'end' => new DateTime('04-02-2021 13:30'),
                'title' => 'Atividade 01',
                'type' => 'T',
                'is_public' => true,
            ],
            [
                'company_id' => 1,
                'user_id' => 2,
                'start' => new DateTime('20-03-2021 10:30'),
                'end' => new DateTime('20-03-2021 11:30'),
                'title' => 'Lembrete 01',
                'type' => 'R',
                'is_public' => true,
            ],
            [
                'company_id' => 1,
                'project_id' => 1,
                'user_id' => 3,
                'start' => new DateTime('21-03-2021 15:00'),
                'end' => new DateTime('21-03-2021 17:00'),
                'title' => 'Atividade Privada',
                'description' => 'Atividade Privada Gestor Silva',
                'type' => 'T',
                'is_public' => false,
            ],
            [
                'company_id' => 1,
                'project_id' => 1,
                'user_id' => 3,
                'start' => new DateTime('24-03-2021 18:00'),
                'end' => new DateTime('24-03-2021 19:00'),
                'title' => 'Atividade 03',
                'description' => 'Atividade 03',
                'type' => 'T',
                'is_public' => true,
            ],
        ];

        foreach ($data as $item) {
            Event::updateOrInsert($item);
        }
    }
}
