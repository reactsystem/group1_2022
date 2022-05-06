<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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

        $uuid1 = Str::uuid();
        \DB::table('various_requests')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'user_id' => 1,
                    'uuid' => $uuid1,
                    'type' => 1,
                    'date' => '2022-5-2',
                    'status' => 0,
                    'reason' => '理由です',
                    'comment' => NULL,
                    'related_id' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            1 =>
                array(
                    'id' => 2,
                    'user_id' => 1,
                    'uuid' => Str::uuid(),
                    'type' => 1,
                    'date' => '2022-5-3',
                    'status' => 0,
                    'reason' => '理由です',
                    'comment' => NULL,
                    'related_id' => $uuid1,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}
