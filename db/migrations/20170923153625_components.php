<?php

use App\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class Components extends Migration
{

	public function up()
	{
		$this->schema->create('components', function(Illuminate\Database\Schema\Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->unique();
			$table->timestamps();
		});

		$this->schema->create('roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('level');
			$table->integer('component_id')->unsigned()->index('component_id')->nullable();
			$table->timestamps();

			$table->foreign('component_id')->references('id')->on('components');

			$table->unique(['name', 'component_id'], 'role_uniq');
		});

		$this->schema->create('user_roles', function (Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('role_id')->unsigned()->index('role_id');
			$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
			$table->timestamps();
		});
	}

	public function down()
	{
		$this->schema->table('roles', function (Blueprint $table)
		{
			$table->dropForeign('roles_component_id_foreign');
		});

		$this->schema->drop('user_roles');
		$this->schema->drop('roles');
		$this->schema->drop('components');
	}

}
