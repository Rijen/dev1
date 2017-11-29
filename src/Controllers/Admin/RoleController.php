<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Role;
use Respect\Validation\Validator as v;

class RoleController extends Controller {

  public function index($request, $response) {
	$roles = Role::all();
	$this->view->render($response, 'admin/role/list.twig', ['roles' => $roles]);
  }

  public function getCreate($request, $response) {
	$this->view->render($response, 'admin/role/create.twig');
  }

  public function postCreate($request, $response) {
	$validation = $this->validator->validate($request, [
		'name' => v::notEmpty()->roleNameAvailable(),
	]);
	if (!$validation->failed()) {
	  Role::create([
		  'name' => $request->getParam('name'),
	  ]);
	  $this->flash->addMessage('info', 'Роль ' . $request->getParam('login') . ' создана');
	  return $response->withRedirect($this->router->pathFor('admin.roles'));
	}

	$_SESSION['old'] = $request->getParams();
	return $response->withRedirect($this->router->pathFor('admin.roles.create'));
  }

  public function getDelete($request, $response, $args) {

	$id		 = $args['id'];
	$role	 = Role::find($id);
	if ($role) {
	  $user_count = $role->users()->count();
	  if ($user_count == 1) {
		$this->flash->addMessage('error', 'Вы не можете удалить используемую роль');
	  } else {
		$role->delete();
		$this->flash->addMessage('info', 'Роль ' . $role->name . ' удалена');
	  }
	  return $response->withRedirect($this->router->pathFor('admin.roles'));
	}

	$status = 404;
	return $response->withStatus($status);
  }

}
