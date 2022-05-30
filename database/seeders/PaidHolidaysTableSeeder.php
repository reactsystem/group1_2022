<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaidHolidaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('paid_holidays')->delete();
        
        \DB::table('paid_holidays')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'amount' => 11,
                'deleted_at' => NULL,
                'created_at' => '2022-05-30 00:00:00',
                'updated_at' => '2022-05-30 11:31:56',
            ),
        ));
        
        
    }
}