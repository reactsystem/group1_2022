<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ConfigurationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('configurations')->delete();

        \DB::table('configurations')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'start' => '09:30:00',
                    'end' => '18:00:00',
                    'time' => '07:45:00',
                    'rest' => '00:45:00',
                    'rest_over' => '00:15:00',
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}
