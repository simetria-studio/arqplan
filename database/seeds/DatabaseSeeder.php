<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CompanySeeder::class);
        $this->call(UserProfileSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(CalendarSeeder::class);
        $this->call(FinanceSeeder::class);
    }
}
