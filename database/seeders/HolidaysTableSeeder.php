<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HolidaysTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('holidays')->delete();

        \DB::table('holidays')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'year' => NULL,
                    'month' => NULL,
                    'day' => 13,
                    'mode' => 0,
                    'name' => '休暇テスト1',
                    'deleted_at' => NULL,
                    'created_at' => '2022-05-11 14:07:28',
                    'updated_at' => '2022-05-11 15:27:50',
                ),
            1 =>
                array(
                    'id' => 4,
                    'year' => NULL,
                    'month' => NULL,
                    'day' => 17,
                    'mode' => 0,
                    'name' => '休暇テスト2',
                    'deleted_at' => NULL,
                    'created_at' => '2022-05-11 14:07:28',
                    'updated_at' => '2022-05-11 15:27:50',
                ),
            2 =>
                array(
                    'id' => 10,
                    'year' => NULL,
                    'month' => NULL,
                    'day' => 27,
                    'mode' => 0,
                    'name' => '休暇テスト3',
                    'deleted_at' => NULL,
                    'created_at' => '2022-05-11 14:07:28',
                    'updated_at' => '2022-05-11 15:27:50',
                ),
        ));


    }
}
