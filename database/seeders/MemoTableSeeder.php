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
                    'user_id' => '1',
                    'memo' => '営業のプロ,身長180cm',
                ],
            ]
        );
    }
}
