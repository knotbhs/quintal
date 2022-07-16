<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEmpresasTable.
 */
class CreateEmpresasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('empresas', function (Blueprint $table) {
			$table->id();
			$table->string('nome_fantasia');
			$table->string('cnpj', 14)->unique();
			$table->json('endereco')->nullable();
			$table->string('razao_social');
			$table->string('inscricao_estadual');
			$table->string('email')->unique();
			$table->rememberToken();
			$table->timestamp();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('empresas');
	}
}
