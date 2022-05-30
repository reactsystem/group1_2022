<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AttendancesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('attendances')->delete();
        
        \DB::table('attendances')->insert(array (
            0 => 
            array (
                'id' => 1,
                'date' => '2022-05-02',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-02 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:18:59',
                'created_at' => '2022-05-02 09:30:00',
            ),
            1 => 
            array (
                'id' => 2,
                'date' => '2022-05-13',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-13 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:25:03',
                'created_at' => '2022-05-13 09:30:00',
            ),
            2 => 
            array (
                'id' => 3,
                'date' => '2022-05-16',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-16 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:25:24',
                'created_at' => '2022-05-16 09:30:00',
            ),
            3 => 
            array (
                'id' => 4,
                'date' => '2022-05-17',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-17 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:25:36',
                'created_at' => '2022-05-17 09:30:00',
            ),
            4 => 
            array (
                'id' => 5,
                'date' => '2022-05-06',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-06 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:21:50',
                'created_at' => '2022-05-06 09:30:00',
            ),
            5 => 
            array (
                'id' => 6,
                'date' => '2022-05-09',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-09 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:23:03',
                'created_at' => '2022-05-09 09:30:00',
            ),
            6 => 
            array (
                'id' => 7,
                'date' => '2022-05-10',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-10 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:23:24',
                'created_at' => '2022-05-10 09:30:00',
            ),
            7 => 
            array (
                'id' => 8,
                'date' => '2022-05-11',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-11 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:23:47',
                'created_at' => '2022-05-11 09:30:00',
            ),
            8 => 
            array (
                'id' => 9,
                'date' => '2022-05-12',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-12 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:56:17',
                'created_at' => '2022-05-12 09:30:00',
            ),
            9 => 
            array (
                'id' => 10,
                'date' => '2022-05-18',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-18 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-18 09:30:00',
            ),
            10 => 
            array (
                'id' => 11,
                'date' => '2022-05-19',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-19 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-19 09:30:00',
            ),
            11 => 
            array (
                'id' => 12,
                'date' => '2022-05-20',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-20 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-20 09:30:00',
            ),
            12 => 
            array (
                'id' => 13,
                'date' => '2022-05-23',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-23 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-23 09:30:00',
            ),
            13 => 
            array (
                'id' => 14,
                'date' => '2022-05-24',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-24 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-24 09:30:00',
            ),
            14 => 
            array (
                'id' => 15,
                'date' => '2022-05-25',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-25 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-25 09:30:00',
            ),
            15 => 
            array (
                'id' => 16,
                'date' => '2022-05-26',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-26 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-26 09:30:00',
            ),
            16 => 
            array (
                'id' => 17,
                'date' => '2022-05-27',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-27 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-27 09:30:00',
            ),
            17 => 
            array (
                'id' => 18,
                'date' => '2022-05-30',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-30 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:56:57',
                'created_at' => '2022-05-30 09:30:00',
            ),
            18 => 
            array (
                'id' => 19,
                'date' => '2022-05-31',
                'user_id' => 1,
                'mode' => 1,
                'comment' => '作業を行いました',
                'status' => 0,
                'time' => '08:30',
                'rest' => '00:45:00',
                'left_at' => '2022-05-31 18:00:00',
                'deleted_at' => NULL,
                'updated_at' => '2022-05-30 11:24:18',
                'created_at' => '2022-05-31 09:30:00',
            ),
        ));
        
        
    }
}