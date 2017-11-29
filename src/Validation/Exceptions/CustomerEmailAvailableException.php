<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class CustomerEmailAvailableException extends ValidationException {

  public static $defaultTemplates = [
	  self::MODE_DEFAULT => [
		  self::STANDARD => 'Email not available'
	  ]

  ];

}
