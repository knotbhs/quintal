<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateAdminsTable.
 */
class CreateAdminsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admins', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('email')->unique()->nullable();
			$table->string('cpf')->unique()->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password')->nullable();
			$table->integer("level")->default(1);
			$table->boolean("ativo")->default(true);
			$table->text("hash")->nullable();
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
		Schema::drop('admins');
	}
}
