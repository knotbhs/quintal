<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entities\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'BRUNO SCARPARI',
            'email' => 'knotbhs@gmail.com',
            'password' => bcrypt('123'),
            'cpf' => '39345880852', 
            'level' => 5, 
            'ativo' => true, 
            'hash' => ''
        ]);
        
    }
}
