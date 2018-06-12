<?php

use App\Database\Migration;

class Users extends Migration
{

	public function up()
	{
		$this->schema->create('users', function(Illuminate\Database\Schema\Blueprint $table)
		{
			// Auto-increment id 
			$table->increments('id');
			$table->string('login')->unique();
			$table->string('email');
			$table->string('password');
			$table->string('family')->nullable();
			$table->string('name')->nullable();
			$table->string('surname')->nullable();
			$table->string('short')->nullable();

			$table->integer('filial_id')->unsigned()->index('filial_id');
			$table->integer('group_id')->unsigned()->index('group_id')->nullable();
			$table->string('work_phone')->nullable();
			$table->string('home_phone')->nullable();
			// Required for Eloquent's created_at and updated_at columns 
			$table->timestamps();
		});
	}

	public function down()
	{
		$this->schema->drop('users');
	}

}
