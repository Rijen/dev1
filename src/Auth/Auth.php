<?php

namespace App\Auth;

use App\Models\User;

class Auth
{

	/**
	 * 
	 * @param string $login
	 * @param string $password
	 * @return boolean
	 */
	public function attemp($login, $password)
	{
		$user = User::where('login', $login)->first();
		if (!$user)
			return false;

		if (password_verify($password, $user->password))
		{
			$_SESSION['user'] = $user->id;
			return true;
		}
		return false;
	}

	public function user()
	{
		if ($this->check())
			return User::find($_SESSION['user']);
	}

	public function check()
	{
		return isset($_SESSION['user']) && User::find($_SESSION['user']);
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}

}
