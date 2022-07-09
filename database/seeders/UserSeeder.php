<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entities\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Funcionario',
            'email' => 'knotbhs2@gmail.com',
            'password' => bcrypt('123'),
            'cpf' => '39345880851', 
            'level' => 1, 
            'ativo' => true, 
            'hash' => ''
        ]);
    }
}
