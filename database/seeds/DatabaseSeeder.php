<?php

use App\Models\SiteSetting;
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
        $this->call(UsersTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(StocksTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);

        SiteSetting::create([
            'linechart' => 0,
            'piechart' => 0,
            'slide' => 0,
            'tab' => 1,
        ]);
    }
}
