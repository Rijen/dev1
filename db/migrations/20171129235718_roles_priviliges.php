<?php

use \App\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class RolesPriviliges extends Migration {

  public function up() {
	$this->schema->create('priviliges', function(Blueprint $table) {
	  $table->integer('id')->unsigned()->unique();
	  $table->string('name')->unique();
	  $table->timestamps();
	});
	$this->schema->create('role_priviliges', function (Blueprint $table) {
	  $table->integer('role_id')->unsigned()->index('role_id');
	  $table->foreign('role_id')->references('id')->on('roles');
	  $table->integer('privilige_id')->unsigned()->index('privilige_id');
	  $table->foreign('privilige_id')->references('id')->on('priviliges');
	  $table->timestamps();
	});
  }

  public function down() {
	$this->schema->drop('role_priviliges');
	$this->schema->drop('priviliges');
  }

}
