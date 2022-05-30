<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserMemosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user_memos')->delete();
        
        \DB::table('user_memos')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 1,
                'memo' => '8月末で転勤予定',
                'created_at' => NULL,
                'updated_at' => '2022-05-30 11:13:30',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'memo' => '2022年度新入社員',
                'created_at' => NULL,
                'updated_at' => '2022-05-30 11:14:17',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 3,
                'memo' => '中途入社2022年度',
                'created_at' => '2022-05-30 11:17:30',
                'updated_at' => '2022-05-30 11:17:30',
            ),
        ));
        
        
    }
}