<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller,
	Respect\Validation\Validator as v;

class AuthController extends Controller {

  public function getLogin($request, $response, $args) {
	$this->view->render($response, 'admin/login.twig');
  }

  public function postLogin($request, $response) {

	$validation = $this->validator->validate($request, [
		'login' => v::noWhitespace()->notEmpty(),
		'password' => v::noWhitespace()->notEmpty(),
	]);

	$_SESSION['old'] = $request->getParams();

	if (!$validation->failed() &&
			$this->adminAuth->attemp($request->getParam('login'), $request->getParam('password'))) {
	  return $response->withRedirect($this->router->pathFor('admin.main'));
	}
	$this->flash->addMessage('error', 'Ошибка авторизации');
	return $response->withRedirect($this->router->pathFor('admin.login'));
  }

  public function getLogout($request, $response) {
	$this->adminAuth->logout();
	return $response->withRedirect($this->router->pathFor('admin.login'));
  }

}
