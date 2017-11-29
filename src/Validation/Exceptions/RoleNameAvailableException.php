<?php

namespace App\Validation\Exceptions;

use Respect\Validation\Exceptions\ValidationException;

class RoleNameAvailableException extends ValidationException {

  public static $defaultTemplates = [
	  self::MODE_DEFAULT => [
		  self::STANDARD => 'Указанное наименование уже используется'
	  ]
  ];

}
