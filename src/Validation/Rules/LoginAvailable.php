<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule,
	App\Models\User;

class LoginAvailable extends AbstractRule {

  public function validate($input) {
	return User::where('login', $input)->count() == 0;
  }

}
