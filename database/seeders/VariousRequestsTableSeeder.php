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
                    'id' => 1,
                    'user_id' => 1,
                    'uuid' => 'a543620b-dc59-40be-94fb-b92034d8b776',
                    'type' => 1,
                    'date' => '2022-05-10',
                    'status' => 0,
                    'reason' => 'これは理由ですこれは理由ですこれは理由ですこれは理由です',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'time' => '2:54',
                    'created_at' => '2022-05-13 15:44:55',
                    'updated_at' => '2022-05-13 15:44:55',
                ),
            1 =>
                array(
                    'id' => 2,
                    'user_id' => 1,
                    'uuid' => '18e526e6-9c0f-4b50-9c58-c9bd4024848e',
                    'type' => 1,
                    'date' => '2022-05-18',
                    'status' => 0,
                    'reason' => 'これは理由ですこれは理由ですこれは理由ですこれは理由です',
                    'comment' => NULL,
                    'related_id' => 'a543620b-dc59-40be-94fb-b92034d8b776',
                    'time' => '2:54',
                    'created_at' => '2022-05-13 15:44:55',
                    'updated_at' => '2022-05-13 15:44:55',
                ),
            2 =>
                array(
                    'id' => 3,
                    'user_id' => 1,
                    'uuid' => '746d7653-6bb0-4159-a268-0b75ad3222ae',
                    'type' => 1,
                    'date' => '2022-05-26',
                    'status' => 0,
                    'reason' => 'これは理由ですこれは理由ですこれは理由ですこれは理由です',
                    'comment' => NULL,
                    'related_id' => 'a543620b-dc59-40be-94fb-b92034d8b776',
                    'time' => '2:54',
                    'created_at' => '2022-05-13 15:44:55',
                    'updated_at' => '2022-05-13 15:44:55',
                ),
        ));


    }
}
