<?php

use App\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class Groups extends Migration
{

	public function up()
	{
		$this->schema->create('groups', function(Illuminate\Database\Schema\Blueprint $table)
		{
			// Auto-increment id 
			$table->increments('id');
			$table->string('name')->unique();
			$table->string('descr');

			// Required for Eloquent's created_at and updated_at columns 
			$table->timestamps();
		});
		$this->schema->table('users', function (Blueprint $table)
		{
			$table->foreign('group_id')->references('id')->on('groups');
		});

		$this->schema->create('user_groups', function (Blueprint $table)
		{
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('group_id')->unsigned()->index('group_id');
			$table->foreign('group_id')->references('id')->on('groups');
			$table->timestamps();
		});
	}

	public function down()
	{
		$this->schema->table('users', function (Blueprint $table)
		{
			$table->dropForeign('users_group_id_foreign');
		});
		$this->schema->drop('groups');
		$this->schema->drop('user_groups');
	}

}
