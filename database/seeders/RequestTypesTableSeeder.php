<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RequestTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('request_types')->delete();

        \DB::table('request_types')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => '残業',
                    'type' => 1,
                    'color' => '#1A1',
                    'created_at' => '2022-05-02 17:04:12',
                    'updated_at' => '2022-05-02 17:04:14',
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => '休暇(有給)',
                    'type' => 2,
                    'color' => '#fcb138',
                    'created_at' => '2022-05-02 17:04:15',
                    'updated_at' => '2022-05-02 17:04:15',
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => '休暇(無給)',
                    'type' => 0,
                    'color' => '#222',
                    'created_at' => '2022-05-02 17:04:16',
                    'updated_at' => '2022-05-02 17:04:16',
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => '休日出勤',
                    'type' => 1,
                    'color' => '#fc38a1',
                    'created_at' => '2022-05-02 17:04:17',
                    'updated_at' => '2022-05-02 17:04:18',
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => '特別休暇',
                    'type' => 2,
                    'color' => '#8d38fc',
                    'created_at' => '2022-05-02 17:04:15',
                    'updated_at' => '2022-05-02 17:04:15',
                ),
        ));


    }
}
