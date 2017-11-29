<?php

use \App\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserRoles extends Migration {

  public function up() {
	$this->schema->create('roles', function(Blueprint $table) {
	  $table->increments('id');
	  $table->string('name')->unique();
	  $table->timestamps();
	});
	$this->schema->table('users', function (Blueprint $table) {
	  $table->integer('role_id')->unsigned()->index('role_id');
	  $table->foreign('role_id')->references('id')->on('roles');
	});
  }

  public function down() {
	$this->schema->table('users', function (Blueprint $table) {
	  $table->dropForeign('users_role_id_foreign');
	  $table->dropColumn('role_id');
	});
	$this->schema->drop('roles');
  }

}
