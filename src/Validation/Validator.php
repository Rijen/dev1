<?php
 //TODO - Необзодимо реализовать оригинильный интерфейc Respect\Validation\Validatable
namespace App\Validation;

use Respect\Validation\Validator as Respect,
	Respect\Validation\Exceptions\NestedValidationException;

class Validator {

  protected $errors;

  public function validate($request, array $rules) {

	foreach ($rules as $field => $rule) {
	  try {
		$rule->setName(ucfirst($field))->assert($request->getParam($field));
	  } catch (NestedValidationException $ex) {
		$this->errors[$field] = $ex->getMessages();
	  }
	}
	$_SESSION['errors'] = $this->errors;
	return $this;
  }

 
  public function validate_args($input, $rules) {
	foreach ($rules as $field => $rule) {
	  try {
		$rule->setName(ucfirst($field))->assert($input[$field]);
	  } catch (NestedValidationException $ex) {
		$this->errors[$field] = $ex->getMessages();
	  }
	}
	return $this;
  }

  public function failed() {
	return !empty($this->errors);
  }

}
