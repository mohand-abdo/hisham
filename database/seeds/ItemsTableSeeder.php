<?php

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = ['اسبير101', 'اسبير102', 'اسبير103'];
        foreach($items as $item){
            $ite = Item::create([
                'name' =>$item,
                'category_id' => 1,
                'purches_price' => 100,
                'sale_price' => 150,
            ]);
        }
    }
}
