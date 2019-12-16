<?php

use App\ModelMasterBankNifty;
use App\ModelMasterSector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder
{

    public function run()
    {

        ModelMasterSector::truncate();
        // do not insert in middle
        //it is a foregin key.
        $arr = [
            'AUTOMOBILE',
            'BANKING',
            'FINANCE',
            'CAPITAL GOODS',
            'FMCG',
            'OIL GAS',
            'TECHNOLOGY',
            'PHARMA',
            'MEDIA',
            'TEXTILE',
            'POWER',
            'CEMENT',
            'REALTY',
            'FERTILISERS',
        ];

        foreach ($arr as $a){
            DB::table('master_sector')->insert([
                'sector' => $a,
            ]);
        }

        DB::table('master_sector')->insert([
           'id' => 99,
            'sector' => 'OTHERS'
        ]);
    }
}
