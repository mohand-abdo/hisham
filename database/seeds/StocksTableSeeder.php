<?php

use Illuminate\Database\Seeder;
use App\Models\Stock;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks = ['المخزن الرئيسي','المعرض'];
        foreach($stocks as $stock){
            Stock::create([
                'name' => $stock,
            ]);
        }
    }
}
