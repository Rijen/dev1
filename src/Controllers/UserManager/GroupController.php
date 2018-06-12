<?php

namespace App\Controllers\UserManager;

use App\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
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

		$id		 = $request->getParam('id');
		$group	 = Group::find($id);
		if ($group)
		{
			$user_count = $group->users()->count();
			if ($user_count > 0)
			{
				return $response->withStatus(200)
								->withJson(['err' => true, 'msg' => $this->lang['group']['del']['m1']]);
			}
			else
			{
				$group->users()->sync([]);
				$group->delete();
				return $response->withStatus(200)
								->withJson(['err' => false, 'msg' => $this->lang['group']['del']['m2']]);
			}
		}

		return $response->withStatus(200)
						->withJson(['err' => true, 'msg' => $this->lang['group']['del']['m3']]);
	}

	public function getUpdate($request, $response, $args)
	{

		$id		 = $args['id'];
		$group	 = Group::find($id);


		if ($group)
		{
			return $this->view->render($response, 'user_manager/group/update.twig', [
						'group' => $group,
			]);
		}

		$status = 404;
		return $response->withStatus($status);
	}

	public function getMembers($request, $response, $args)
	{

		$id = $args['id'];

		$group	 = Group::find($id);
		$users	 = User::all();
		$ans	 = [];
		if ($group)
		{
			$ans = [
				'a_attr' => ['data-group' => $id],
				'state'	 => ['opened' => true],
				'text'	 => $this->lang['user']['all'],
				'id'	 => 0,
				'icon'=>'fas fa-users'
			];
			foreach ($users as $user)
			{
				$ans['children'][] = [
					'id'	 => $user->id,
					'text'	 => $user->name . ' ' . $user->family,
					'state'	 => ['selected' => $user->groups->contains($id)],
					'icon'=>'fas fa-user'
				];
			}
		}

		return $response->withStatus(200)
						->withJson($ans);
	}

	public function postUpdate($request, $response, $args)
	{
		$id		 = $args['id'];
		$group	 = Group::find($id);
		if ($group)
		{
			$members = json_decode($request->getParam('members'));

			$sync = [];
			foreach ($members as $member)
			{
				if ($member->state->selected)
				{
					$sync[] = $member->id;
				}
			}
			$group->users()->sync($sync);
			$group->descr = $request->getParam('descr');
			$group->save();
			$this->flash->addMessage('info', 'Роль ' . $group->name . ' изменена');
			return $response->withRedirect($this->router->pathFor('user_manager.group'));
		}

		$status = 404;
		return $response->withStatus($status);
	}

}
