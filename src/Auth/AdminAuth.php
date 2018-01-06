<?php

namespace App\Auth;

use App\Models\User;

class AdminAuth {

  /**
   * 
   * @param string $login
   * @param string $password
   * @return boolean
   */
  public function attemp($login, $password) {
	$user = User::where('login', $login)->first();
	if (!$user)
	  return false;

	if (password_verify($password, $user->password) 
			&& $user->role->priviliges->contains(1)) {
	  $_SESSION['admin_user'] = $user->id;
	  return true;
	}
	return false;
  }

  public function user() {
	if ($this->check())
	  return User::find($_SESSION['admin_user']);
  }

  public function check() {
	return isset($_SESSION['admin_user']) && User::find($_SESSION['admin_user']);
  }

  public function logout() {
	unset($_SESSION['admin_user']);
  }

}
