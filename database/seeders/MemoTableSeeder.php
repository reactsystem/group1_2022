<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_memos')->insert(
            [
                0 => [
                    'id' => 1,
                    'user_id' => '1',
                    'memo' => '営業のプロ,身長180cm',
                ],
                1 => [
                    'id' => 2,
                    'user_id' => '2',
                    'memo' => '新入社員,2022',
                ],
            ]
        );
    }
}
