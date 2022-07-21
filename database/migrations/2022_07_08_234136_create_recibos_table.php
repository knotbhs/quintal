<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateRecibosTable.
 */
class CreateRecibosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recibos', function (Blueprint $table) {
			$table->increments('id');
			$table->foreignId('admin_id')->constrained('users');
			$table->foreignId('colaborador_id')->constrained('users');
			$table->foreignId('empresa_id')->constrained('empresas');
			$table->float('valor')->nullable();
			$table->string('servico')->nullable();
			$table->text('descricao')->nullable();
			$table->date('data');
			$table->boolean('visible')->default(true);
			$table->integer('last_edit');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('recibos');
	}
}
