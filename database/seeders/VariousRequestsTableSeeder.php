<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VariousRequestsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('various_requests')->delete();

        \DB::table('various_requests')->insert(array(
            0 =>
                array(
                    'id' => 4,
                    'user_id' => 1,
                    'uuid' => '10b4375a-0242-43e2-8857-8df8bff39752',
                    'type' => 2,
                    'date' => '2022-06-01',
                    'status' => 1,
                    'reason' => '',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'holidays_key' => '1:1',
                    'time' => '',
                    'created_at' => '2022-05-30 11:31:56',
                    'updated_at' => '2022-05-30 11:32:33',
                ),
            1 =>
                array(
                    'id' => 5,
                    'user_id' => 1,
                    'uuid' => '10aa76fc-1428-4a2d-a52d-4a547a338a4c',
                    'type' => 1,
                    'date' => '2022-05-27',
                    'status' => 1,
                    'reason' => '作業が長引きました。',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'holidays_key' => NULL,
                    'time' => '1:25',
                    'created_at' => '2022-05-30 11:32:20',
                    'updated_at' => '2022-05-30 11:32:30',
                ),
            2 =>
                array(
                    'id' => 6,
                    'user_id' => 1,
                    'uuid' => '7bfc5b10-106d-4198-9236-538a6b4daf9f',
                    'type' => 3,
                    'date' => '2022-05-31',
                    'status' => 0,
                    'reason' => '買い物に行きたいです',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'holidays_key' => NULL,
                    'time' => '',
                    'created_at' => '2022-05-30 11:35:46',
                    'updated_at' => '2022-05-30 11:35:46',
                ),
            3 =>
                array(
                    'id' => 7,
                    'user_id' => 1,
                    'uuid' => 'f6cb0671-1673-407d-98b3-e96686d7d026',
                    'type' => 5,
                    'date' => '2022-05-27',
                    'status' => 2,
                    'reason' => '',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'holidays_key' => NULL,
                    'time' => '',
                    'created_at' => '2022-05-30 11:36:18',
                    'updated_at' => '2022-05-30 11:36:27',
                ),
            4 =>
                array(
                    'id' => 8,
                    'user_id' => 1,
                    'uuid' => 'c8ecd571-6aae-4e99-83e2-0b590f740f96',
                    'type' => 1,
                    'date' => '2022-05-24',
                    'status' => 3,
                    'reason' => '緊急の対応を行いました',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'holidays_key' => NULL,
                    'time' => '1:00',
                    'created_at' => '2022-05-30 11:37:04',
                    'updated_at' => '2022-05-30 11:37:09',
                ),
        ));


    }
}
