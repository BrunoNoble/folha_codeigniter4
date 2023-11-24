<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class User extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_Br');

        for ($i=0; $i <= 10; $i++)
        {
          $data = [
              'name'=> $faker->name(),
              'email'=> $faker->email(),
              'passowrd'=> $faker->password(),
              
          ];
          $this->db->table('users')->insert($data);
        }
    }
}
