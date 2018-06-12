<?php

namespace App\Controllers\Users;

use App\Controllers\Controller,
	Respect\Validation\Validator as v;

class AuthController extends Controller
{

	public function getLogin($request, $response, $args)
	{
		$this->view->render($response, 'admin/login.twig');
	}

	public function postLogin($request, $response)
	{

		$validation = $this->validator->validate($request, [
			'login'		 => v::noWhitespace()->notEmpty(),
			'password'	 => v::noWhitespace()->notEmpty(),
		]);

		$_SESSION['old'] = $request->getParams();

		if (!$validation->failed() &&
				$this->Auth->attemp($request->getParam('login'), $request->getParam('password')))
		{
			return $response->withRedirect($this->router->pathFor('main'));
		}
		$this->flash->addMessage('error', $this->lang['login']['e1']);
		return $response->withRedirect($this->router->pathFor('login'));
	}

	public function getLogout($request, $response)
	{
		$this->Auth->logout();
		return $response->withRedirect($this->router->pathFor('login'));
	}

}
