<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert(
            [
                0 =>
                    [
                        'name' => 'テストユーザー',
                        'email' => 'admin@localhost.com',
                        'password' => '$2y$10$flQ9qxirnKqELH/ubdQqK.GXDb7v.6HZy1kdQxJtGemwNDzukO6WS',
                        'department' => 1,
                        'employee_id' => 1,
                        'group_id' => 1,
                        'joined_date' => date_create(),
                    ],
                2 => [
                    'name'=>'tester',
                    'email'=>'tester@test.jp',
                    'password' => '$2y$10$flQ9qxirnKqELH/ubdQqK.GXDb7v.6HZy1kdQxJtGemwNDzukO6WS',
                    'department'=>'1',
                    'employee_id'=>'11111111',
                    'group_id'=>'0',
                    'joined_date'=>'2022-05-02',
                ],
                3 => [
                    'name'=>'admin',
                    'email'=>'admin@test.jp',
                    'password' => '$2y$10$flQ9qxirnKqELH/ubdQqK.GXDb7v.6HZy1kdQxJtGemwNDzukO6WS',
                    'department'=>'2',
                    'employee_id'=>'99999999',
                    'group_id'=>'1',
                    'joined_date'=>'2022-05-01',
                ]
            ]
        );
    }
}
