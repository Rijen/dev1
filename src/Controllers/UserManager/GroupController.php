<?php

namespace App\Controllers\UserManager;

use App\Controllers\Controller;
use App\Models\Group;
use Respect\Validation\Validator as v;

class GroupController extends Controller
{

	public function index($request, $response)
	{
		$items = Group::all();
		$this->view->render($response, 'user_manager/group/list.twig', ['groups' => $items]);
	}

	public function getCreate($request, $response)
	{
		$this->view->render($response, 'user_manager/group/create.twig');
	}

	public function postCreate($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'name' => v::notEmpty()->groupNameAvailable(),
		]);
		if (!$validation->failed())
		{
			Group::create([
				'name'	 => $request->getParam('name'),
				'descr'	 => $request->getParam('descr'),
			]);
			$this->flash->addMessage('info', 'Роль ' . $request->getParam('name') . ' создана');
			return $response->withRedirect($this->router->pathFor('user_manager.group'));
		}

		$_SESSION['old'] = $request->getParams();
		return $response->withRedirect($this->router->pathFor('user_manager.group.create'));
	}

	public function getDelete($request, $response, $args)
	{

		$id		 = $args['id'];
		$role	 = Role::find($id);
		if ($role)
		{
			$user_count = $role->users()->count();
			if ($user_count > 0)
			{
				$this->flash->addMessage('error', 'Вы не можете удалить используемую роль');
			}
			else
			{
				$role->priviliges()->sync([]);
				$role->delete();
				$this->flash->addMessage('info', 'Роль ' . $role->name . ' удалена');
			}
			return $response->withRedirect($this->router->pathFor('roles'));
		}

		$status = 404;
		return $response->withStatus($status);
	}

	public function getUpdate($request, $response, $args)
	{

		$id			 = $args['id'];
		$group		 = Group::find($id);
		if ($group)
		{
			return $this->view->render($response, 'user_manager/group/update.twig', ['group' => $group]);
		}

		$status = 404;
		return $response->withStatus($status);
	}

	public function postUpdate($request, $response, $args)
	{
		$id		 = $args['id'];
		$role	 = Role::find($id);
		if ($role)
		{
			$params	 = $request->getParams();
			$ids	 = [];
			foreach ($params as $k => $v)
			{
				preg_match('/^privilige_([0-9]+)$/u', $k, $r);
				if (!empty($r) && isset($r[1]) && is_numeric($r[1]))
				{
					$ids[] = $r[1];
				}
			}

			$role->priviliges()->sync($ids);

			$this->flash->addMessage('info', 'Роль ' . $role->name . ' изменена');
			return $response->withRedirect($this->router->pathFor('roles'));
		}

		$status = 404;
		return $response->withStatus($status);
	}

}
