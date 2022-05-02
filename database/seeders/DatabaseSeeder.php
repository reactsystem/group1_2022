<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->fill([
            'name'=>'tester',
            'email'=>'tester@test.jp',
            'password'=>'testtest',
            'department'=>'1',
            'employee_id'=>'11111111',
            'group_id'=>'0',
            'joined_date'=>'2022-05-02',
            'paid_holiday'=>'10',
            
        ]);
        $user->save();

        $admin_user = new User();
        $admin_user->fill([
            'name'=>'admin',
            'email'=>'admin@test.jp',
            'password'=>'testtest',
            'department'=>'2',
            'employee_id'=>'99999999',
            'group_id'=>'1',
            'joined_date'=>'2022-05-01',
            'paid_holiday'=>'999',
        ]);
        $admin_user->save();

        $Attendance = new Attendance();
        $Attendance->fill([
            'date' => '2022-05-02',
        	'user_id'=> '1',
            'mode'	=> '1',
            'comment'	=> '営業を頑張りました',
            'status'	=> '0',
        ]);
        $Attendance->save();

        $Attendance2 = new Attendance();
        $Attendance2->fill([
            'date' => '2022-05-01',
        	'user_id'=> '2',
            'mode'	=> '0',
            'comment'	=> 'お金の計算をしました',
            'status'	=> '0',
        ]);
        $Attendance2->save();

        $department = new Department();
        $department->fill([
            'id'=> '1',
            'name' => '営業',
        ]);
        $department->save();

        $department2 = new Department();
        $department2->fill([
        	'id'=> '2',
            'name' => '経理',

        ]);
        $department2->save();

        $department3 = new Department();
        $department3->fill([
            'id'=> '3',
            'name' => '総務',
        ]);
        $department3->save();
        
        $department4 = new Department();
        $department4->fill([
            'id'=> '4',
            'name' => '人事',
        ]);
        $department4->save();
        
        $department5 = new Department();
        $department5->fill([
            'id'=> '5',
            'name' => 'システム開発',
        ]);
        $department5->save();

    }
}