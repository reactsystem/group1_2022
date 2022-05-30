<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(UserTableSeeder::class);
        //$this->call(AttendanceTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(VariousRequestsTableSeeder::class);
        $this->call(RequestTypesTableSeeder::class);
//        $this->call(MemoTableSeeder::class);
        $this->call(HolidaysTableSeeder::class);
        $this->call(ConfigurationsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UserMemosTableSeeder::class);
        $this->call(PaidHolidaysTableSeeder::class);
        $this->call(AttendancesTableSeeder::class);
    }
}
