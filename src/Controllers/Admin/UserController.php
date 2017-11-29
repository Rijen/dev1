<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\User;
use Respect\Validation\Validator as v;

class UserController extends Controller {

  public function index($request, $response) {
	$users = User::all();
	$this->view->render($response, 'admin/user/list.twig', ['users' => $users]);
  }

  public function getCreate($request, $response) {
	$this->view->render($response, 'admin/user/create.twig');
  }

  public function postCreate($request, $response) {
	$validation = $this->validator->validate($request, [
		'name' => v::notEmpty(),
		'login' => v::noWhitespace()->notEmpty()->loginAvailable(),
		'email' => v::noWhitespace()->notEmpty()->email(),
		'password' => v::noWhitespace()->notEmpty(),
	]);
	if (!$validation->failed()) {
	  User::create([
		  'name' => $request->getParam('name'),
		  'login' => $request->getParam('login'),
		  'email' => $request->getParam('email'),
		  'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
	  ]);
	  $this->flash->addMessage('info', 'Пользователь ' . $request->getParam('login') . ' создан');
	  return $response->withRedirect($this->router->pathFor('admin.users'));
	}

	$_SESSION['old'] = $request->getParams();
	return $response->withRedirect($this->router->pathFor('admin.users.create'));
  }

  public function getUpdate($request, $response, $args) {

	$id = $args['id'];
	$user = User::find($id);
	if ($user) {
	  return $this->view->render($response, 'admin/user/update.twig', ['user' => $user]);
	}

	$status = 404;
	return $response->withStatus($status);
  }

  public function postUpdate($request, $response, $args) {
	$id = $args['id'];
	$user = User::find($id);
	if ($user) {
	  $validation = $this->validator->validate($request, [
		  'name' => v::notEmpty(),
		  'email' => v::noWhitespace()->notEmpty()->email(),
		  'password' => v::noWhitespace(),
	  ]);

	  if (!$validation->failed()) {
		$user->name = $request->getParam('name');
		$user->email = $request->getParam('email');
		if ($request->getParam('password')) {
		  $user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT);
		}
		$user->save();
		$this->flash->addMessage('info', 'Пользователь ' . $user->login . ' изменен');
		return $response->withRedirect($this->router->pathFor('admin.users'));
	  }

	  return $response->withRedirect('/admin/users/'.$id.'/update');
	}

	$status = 404;
	return $response->withStatus($status);
  }

  public function getDelete($request, $response, $args) {

	$id = $args['id'];
	$user = User::find($id);
	if ($user) {
	  $user_count = User::all()->count();
	  if ($user_count == 1) {
		$this->flash->addMessage('error', 'Вы не можете удалить единственного пользователя системы');
	  } elseif ($user->id == $this->adminAuth->user()->id) {
		$this->flash->addMessage('error', 'Вы не можете удалить текущего пользователя системы');
	  } else {
		$user->delete();
		$this->flash->addMessage('info', 'Пользователь ' . $user->login . ' удален');
	  }
	  return $response->withRedirect($this->router->pathFor('admin.users'));
	}

	$status = 404;
	return $response->withStatus($status);
  }

}
