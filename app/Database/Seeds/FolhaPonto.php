<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class FolhaPonto extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_Br');

        $startDate = now()->startOfMonth()->setDate(2023, 09, 1);
        $endDate = now()->endOfMonth()->setDate(2023, 11, 24);
        for ($i=0; $i <= 10; $i++)
        {
          $data = [
              'user_id'=> 1,
              'entry_date'=> $faker->email(),
              'passowrd'=> $faker->password(),
              
          ];
          $this->db->table('folha_pontos')->insert($data);
        }
    }
}
