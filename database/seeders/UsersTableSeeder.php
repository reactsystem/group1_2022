<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '社員A',
                'email' => 'admin@localhost.com',
                'email_verified_at' => NULL,
                'password' => '$2y$10$flQ9qxirnKqELH/ubdQqK.GXDb7v.6HZy1kdQxJtGemwNDzukO6WS',
                'remember_token' => NULL,
                'department' => 1,
                'employee_id' => 1,
                'group_id' => 1,
                'joined_date' => '2020-05-29',
                'last_login' => '2022-05-30',
                'left_date' => NULL,
                'updated_at' => '2022-05-30 11:13:30',
                'created_at' => '2022-05-30 11:11:04',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '社員B',
                'email' => 'tester@test.jp',
                'email_verified_at' => NULL,
                'password' => '$2y$10$flQ9qxirnKqELH/ubdQqK.GXDb7v.6HZy1kdQxJtGemwNDzukO6WS',
                'remember_token' => NULL,
                'department' => 2,
                'employee_id' => 2,
                'group_id' => 0,
                'joined_date' => '2022-04-01',
                'last_login' => NULL,
                'left_date' => NULL,
                'updated_at' => '2022-05-30 11:14:17',
                'created_at' => '2022-05-30 11:11:04',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '社員C',
                'email' => 'admin@test.jp',
                'email_verified_at' => NULL,
                'password' => '$2y$10$flQ9qxirnKqELH/ubdQqK.GXDb7v.6HZy1kdQxJtGemwNDzukO6WS',
                'remember_token' => NULL,
                'department' => 5,
                'employee_id' => 3,
                'group_id' => 1,
                'joined_date' => '2022-05-01',
                'last_login' => NULL,
                'left_date' => NULL,
                'updated_at' => '2022-05-30 11:14:48',
                'created_at' => '2022-05-30 11:11:04',
            ),
        ));
        
        
    }
}