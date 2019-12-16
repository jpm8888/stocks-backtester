<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            BankNiftyStocksSeeder::class,
            NiftyStocksSeeder::class,
            SectorSeeder::class,
        ]);

    }
}
