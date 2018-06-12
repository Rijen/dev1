<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule,
	App\Models\Group;

class GroupNameAvailable extends AbstractRule {

  public function validate($input) {
	return Group::where('name', $input)->count() == 0;
  }

}
