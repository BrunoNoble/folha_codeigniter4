<?php

namespace App\Database\Seeds;

use Carbon\Carbon;
use CodeIgniter\Database\Seeder;
use Faker\Factory;

class FolhaPonto extends Seeder
{


    public function run()
    {

        $faker = Factory::create('pt_Br');

        $startDate = Carbon::now()->startOfMonth()->setDate(2023, 9, 1);
        $endDate = Carbon::now()->endOfMonth()->setDate(2023, 11, 24);
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate)
        {
            if (!Carbon::parse($currentDate)->isWeekend())
            {
                $data = [
                    'user_id'=> 1,
                    'entry_date'=> $currentDate,
                    'exit_date'=> $currentDate,
                    'entry_hour' => $this->generateRandomTime(8,9),
                    'exit_hour'=> $this->generateRandomTime(17 , 18),
                    'break_entry'=> '12:00:00',
                    'break_exit'=> '13:00:00'
                ];
            }else{
                $data = [
                    'user_id'=> 1,
                    'entry_date'=> $currentDate,
                    'exit_date'=> $currentDate,
                    'entry_hour' => '00:00:00',
                    'exit_hour'=> '00:00:00',
                    'break_entry'=> '00:00:00',
                    'break_exit'=> '00:00:00'
                ];
            }


          $this->db->table('folha_pontos')->insert($data);
           $currentDate->addDay(); // Move to the next day
        }
    }
    private function generateRandomTime($startHour, $endHour): string
    {
        $hour = rand($startHour, $endHour);
        $minute = rand(0, 12);

        return sprintf('%02d:%02d:00', $hour, $minute);
    }
}
