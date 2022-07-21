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
			$table->string('nome_fantasia')->nullable();
			$table->string('cnpj', 14)->unique()->nullable();
			$table->json('endereco')->nullable();
			$table->string('razao_social')->nullable();
			$table->string('inscricao_estadual')->nullable();
			$table->string('email')->unique()->nullable();
			$table->rememberToken();
			$table->timestamps();
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
