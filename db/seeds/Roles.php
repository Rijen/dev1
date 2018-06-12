<?php

use App\Database\Seed;
use App\Models\User;
use App\Models\Component;
use App\Models\Role;

//use App\Models\Role;
//use App\Models\Privilige;

class Roles extends Seed
{

	public function run()
	{
		$component = Component::create(['name' => 'User managament']);

		$role = Role::create(['name' => 'Editor', 'component_id' => $component->id, 'level' => Role::EDIT]);

		$component2 = Component::create(['name' => 'Sample']);

		Role::create(['name' => 'Read-only', 'component_id' => $component2->id, 'level' => Role::VIEW]);
		Role::create(['name' => 'Editor', 'component_id' => $component2->id, 'level' => Role::EDIT]);

		User::first()->groups()->attach($role->id);
	}

}
