<?php

use App\Database\Seed;
use App\Models\User;

class UsersSeeder extends Seed {

  /**
   * Run Method.
   *
   * Write your database seeder using this method.
   *
   * More information on writing seeders is available here:
   * http://docs.phinx.org/en/latest/seeding.html
   */
  public function run() {
	User::create([
		'name' => 'Administrator',
		'login' => 'admin',
		'email' => 'example@example.com',
		'password' => password_hash('111', PASSWORD_DEFAULT),
	]);
  }

}
