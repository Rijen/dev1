<?php

namespace App\Controllers\UserManager;

use App\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Respect\Validation\Validator as v;

class UserController extends Controller
{

	public function index($request, $response)
	{
		$users = User::all();
		$this->view->render($response, 'user_manager/user/list.twig', ['users' => $users]);
	}

	public function getCreate($request, $response)
	{
		$roles = Role::all();
		$this->view->render($response, 'admin/user/create.twig', ['roles' => $roles]);
	}

	public function postCreate($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'name'		 => v::notEmpty(),
			'login'		 => v::noWhitespace()->notEmpty()->loginAvailable(),
			'email'		 => v::noWhitespace()->notEmpty()->email(),
			'password'	 => v::noWhitespace()->notEmpty(),
			'role_id'	 => v::noWhitespace()->notEmpty(),
		]);
		if (!$validation->failed())
		{
			User::create([
				'name'		 => $request->getParam('name'),
				'login'		 => $request->getParam('login'),
				'email'		 => $request->getParam('email'),
				'password'	 => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
				'role_id'	 => $request->getParam('role_id')
			]);
			$this->flash->addMessage('info', 'Пользователь ' . $request->getParam('login') . ' создан');
			return $response->withRedirect($this->router->pathFor('users'));
		}

		$_SESSION['old'] = $request->getParams();
		return $response->withRedirect($this->router->pathFor('users.create'));
	}

	public function getUpdate($request, $response, $args)
	{

		$id		 = $args['id'];
		$user	 = User::find($id);
		$roles	 = Role::all();
		if ($user)
		{
			return $this->view->render($response, 'admin/user/update.twig', ['user' => $user, 'roles' => $roles]);
		}

		$status = 404;
		return $response->withStatus($status);
	}

	public function postUpdate($request, $response, $args)
	{
		$id		 = $args['id'];
		$user	 = User::find($id);
		if ($user)
		{
			$validation = $this->validator->validate($request, [
				'name'		 => v::notEmpty(),
				'email'		 => v::noWhitespace()->notEmpty()->email(),
				'password'	 => v::noWhitespace(),
				'role_id'	 => v::noWhitespace()->notEmpty(),
			]);

			if (!$validation->failed())
			{
				$user->name		 = $request->getParam('name');
				$user->email	 = $request->getParam('email');
				$user->role_id	 = $request->getParam('role_id');
				if ($request->getParam('password'))
				{
					$user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT);
				}
				$user->save();
				$this->flash->addMessage('info', 'Пользователь ' . $user->login . ' изменен');
				return $response->withRedirect($this->router->pathFor('users'));
			}

			return $response->withRedirect('/users/' . $id . '/update');
		}

		$status = 404;
		return $response->withStatus($status);
	}

	public function getDelete($request, $response, $args)
	{

		$id		 = $args['id'];
		$user	 = User::find($id);
		if ($user)
		{
			$user_count = User::all()->count();
			if ($user_count == 1)
			{
				$this->flash->addMessage('error', 'Вы не можете удалить единственного пользователя системы');
			}
			elseif ($user->id == $this->Auth->user()->id)
			{
				$this->flash->addMessage('error', 'Вы не можете удалить текущего пользователя системы');
			}
			else
			{
				$user->delete();
				$this->flash->addMessage('info', 'Пользователь ' . $user->login . ' удален');
			}
			return $response->withRedirect($this->router->pathFor('users'));
		}

		$status = 404;
		return $response->withStatus($status);
	}

}
