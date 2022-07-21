<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entities\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Empresa::create([
            'nome_fantasia' => 'Quintal Niger',
            'cnpj' => '32325767000187',
            'endereco' => '{"rua":"RUA PASCHOAL BARDARO", "num":1996, "bairro": "JD BOTÂNICO", "cidade": "RIBEIRÃO PRETO", "estado":"SP", "cep": "14025-665"}',
            'razao_social' => 'LEIPNER EMPREENDIMENTOS EIRELI',
            'inscricao_estadual' => '797440052116',
            'email' => 'leipnerempreendimentos@gmail.com'
        ]);
    }
}
