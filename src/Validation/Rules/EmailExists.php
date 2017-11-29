<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule,
	App\Models\User;

class EmailExists extends AbstractRule {

  public function validate($input) {
	return User::where('email', $input)->count() != 0;
  }

}
