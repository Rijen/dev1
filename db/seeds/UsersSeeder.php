<?php

use App\Database\Seed;
use App\Models\User;
use App\Models\Group;
use App\Models\Filial;

//use App\Models\Role;
//use App\Models\Privilige;

class UsersSeeder extends Seed
{

	public function run()
	{
		Filial::create(['name' => 'Filial 1']);
		Filial::create(['name' => 'Filial 2']);
//
//		Privilige::create(['id' => 1, 'name' => 'Admin interface']);
//		Privilige::create(['id' => 2, 'name' => 'User interface']);
//
//		Role::find(1)->priviliges()->attach([1, 2]);
//		Role::find(2)->priviliges()->attach(2);
		Group::create(['name' => 'Devel', 'descr' => 'Developer test group']);
		Group::create(['name' => 'Test', 'descr' => '1234']);
		Group::create(['name' => 'Test 2', 'descr' => '1234']);

		User::create([
			'name'		 => 'Administrator',
			'login'		 => 'admin',
			'email'		 => 'example@example.com',
			'password'	 => password_hash('111', PASSWORD_DEFAULT),
			'group_id'	 => 1,
			'filial_id'	 => 1,
		]);
		User::create([
			'name'		 => 'Test',
			'login'		 => 'test',
			'email'		 => 'example@example.com',
			'password'	 => password_hash('111', PASSWORD_DEFAULT),
			'group_id'	 => 2,
			'filial_id'	 => 2,
		]);
		User::first()->groups()->attach([1, 2]);
		User::find(2)->groups()->attach([3, 2]);
	}

}
