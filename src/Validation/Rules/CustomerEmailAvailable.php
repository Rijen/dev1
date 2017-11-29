<?php

namespace App\Validation\Rules;

use Respect\Validation\Rules\AbstractRule,
	App\Models\Customer;

class CustomerEmailAvailable extends AbstractRule {

  public function validate($input) {
	return Customer::where('email', $input)->count() == 0;
  }

}
