<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::create([
            'name' =>'مهند عبد الوهاب البدوي',
            'phone' => [545454545],
            'address'=> 'امدرمان'
        ]);
    }
}
