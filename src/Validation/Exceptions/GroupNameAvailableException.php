<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class GroupNameAvailableException extends ValidationException {

  public static $defaultTemplates = [
	  self::MODE_DEFAULT => [
		  self::STANDARD => 'Current name not available'
	  ]
  ];

}
