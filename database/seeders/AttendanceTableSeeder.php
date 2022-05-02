<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceTableSeeder extends Seeder
{
    public function run() {
        DB::table('attendances')->insert(
            [
                0 => [
                    'date' => '2022-05-02',
                    'user_id'=> '1',
                    'mode'	=> '1',
                    'comment'	=> '営業を頑張りました',
                    'status'	=> '0',
                ],
                1 => [
                    'date' => '2022-05-01',
                    'user_id'=> '2',
                    'mode'	=> '0',
                    'comment'	=> 'お金の計算をしました',
                    'status'	=> '0',
                ]
            ]
        );
    }
}
