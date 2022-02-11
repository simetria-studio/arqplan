<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;

class UserSeeder extends Seeder
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
                'is_super_admin' => 1,
                'name' => 'Danilo',
                'lastname' => 'Molina',
                'email' => 'danilo@molina34.com',
                'password' => '$2y$10$LfQjWSdjw8mY7uItD/XEA.KsiAkEGG49TBwLmilYLo7FsB23pIQv2',
                'cpf' => '345.587.508-46',
                'company_id' => 1,
            ],
            [
                'is_super_admin' => 1,
                'name' => 'Fabio',
                'lastname' => 'Vagner',
                'email' => 'felipephplow@gmail.com',
                'password' => Hash::make('powerfull'),
                'cpf' => '12547843',
                'company_id' => 1,
            ],
            [
                'name' => 'Gestor',
                'lastname' => 'Silva',
                'email' => 'user01@molina34.com',
                'password' => Hash::make('User01'),
                'cpf' => '033.739.080-00',
                'company_id' => 1,
            ],
            [
                'name' => 'Funcionário',
                'lastname' => 'Silva',
                'email' => 'user02@molina34.com',
                'password' => Hash::make('User02'),
                'cpf' => '353.916.330-10',
                'company_id' => 1,
            ],
        ];

        foreach ($data as $item) {
            User::updateOrInsert($item);
        }

        $user02 = User::where('email', 'user01@molina34.com')->firstOrFail();
        $user02->profiles()->attach(UserProfile::where('code', 'ADMIN')->firstOrFail());

        $user03 = User::where('email', 'user02@molina34.com')->firstOrFail();
        $user03->profiles()->attach(UserProfile::where('code', 'PROJECT_VIEW')->firstOrFail());
        $user03->profiles()->attach(UserProfile::where('code', 'CLIENT_VIEW')->firstOrFail());
        $user03->profiles()->attach(UserProfile::where('code', 'USER_EDIT')->firstOrFail());
    }
}
