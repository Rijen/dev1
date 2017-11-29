<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule,
	App\Models\Role;

class RoleNameAvailable extends AbstractRule {

  public function validate($input) {
	return Role::where('name', $input)->count() == 0;
  }

}
