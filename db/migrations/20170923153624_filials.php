<?php

use App\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class Filials extends Migration
{

	public function up()
	{
		$this->schema->create('filials', function(Illuminate\Database\Schema\Blueprint $table)
		{
			// Auto-increment id 
			$table->increments('id');
			$table->string('name')->unique();

			// Required for Eloquent's created_at and updated_at columns 
			$table->timestamps();
		});
		$this->schema->table('users', function (Blueprint $table)
		{
			$table->foreign('filial_id')->references('id')->on('filials');
		});
	}

	public function down()
	{
		$this->schema->table('users', function (Blueprint $table)
		{
			$table->dropForeign('users_filial_id_foreign');
		});
		$this->schema->drop('filials');
	}

}
