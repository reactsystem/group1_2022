<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            [
                0 => [
                    'id' => '1',
                    'name' => '営業',
                ],
                1 => [
                    'id' => '2',
                    'name' => '経理',
                ],
                2 => [
                    'id' => '3',
                    'name' => '総務',
                ],
                3 => [
                    'id' => '4',
                    'name' => '人事',
                ],
                4 => [
                    'id' => '5',
                    'name' => 'システム開発',
                ]
            ]
        );
    }
}
