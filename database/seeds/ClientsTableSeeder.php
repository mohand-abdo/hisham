<?php

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'name' => 'محمد عبد الوهاب', 
            'phone' => [545454545], 
            'address' => 'امدرمان'
        ]);
    }
}
