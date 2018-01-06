<?php

use App\Database\Seed;
use App\Models\User;
use App\Models\Role;
use App\Models\Privilige;

class UsersSeeder extends Seed {

  public function run() {
	Role::create(['name' => 'Administrator']);
	Role::create(['name' => 'User']);

	Privilige::create(['id' => 1, 'name' => 'Admin interface']);
	Privilige::create(['id' => 2, 'name' => 'User interface']);

	Role::find(1)->priviliges()->attach([1, 2]);
	Role::find(2)->priviliges()->attach(2);

	User::create([
		'name'		 => 'Administrator',
		'login'		 => 'admin',
		'email'		 => 'example@example.com',
		'password'	 => password_hash('111', PASSWORD_DEFAULT),
		'role_id'	 => Role::first()->id
	]);
  }

}
