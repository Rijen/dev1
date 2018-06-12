<?php

namespace App\Controllers\UserManager;

use App\Controllers\Controller;
use App\Models\User;
use App\Models\Filial;
use App\Models\Group;
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
		$filials = Filial::all();
		$this->view->render($response, 'user_manager/user/create.twig', ['filials' => $filials]);
	}

	public function postCreate($request, $response)
	{
		$validation = $this->validator->validate($request, [
			'name'		 => v::notEmpty(),
			'login'		 => v::noWhitespace()->notEmpty()->loginAvailable(),
			'email'		 => v::noWhitespace()->notEmpty()->email(),
			'password'	 => v::noWhitespace()->notEmpty(),
//			'filial_id'	 => v::noWhitespace()->notEmpty(),
		]);
		var_dump($validation);
		if (!$validation->failed())
		{
			$f		 = $request->getParam('filial_id');
			$user	 = User::create([
						'name'		 => $request->getParam('name'),
						'family'	 => $request->getParam('family'),
						'surname'	 => $request->getParam('surname'),
						'short'		 => $request->getParam('short'),
						'work_phone' => $request->getParam('work_phone'),
						'home_phone' => $request->getParam('home_phone'),
						'email'		 => $request->getParam('email'),
						'login'		 => $request->getParam('login'),
						'password'	 => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
						'filial_id'	 => $f ? $f : 1
			]);

			$this->photo_upload($request, $user);

			$this->flash->addMessage('info', 'Пользователь ' . $request->getParam('login') . ' создан');
			return $response->withRedirect($this->router->pathFor('user_manager.user'));
		}
//		print_r($_SESSION['errors']);
		$_SESSION['old'] = $request->getParams();
		return $response->withRedirect($this->router->pathFor('user_manager.user.create'));
	}

	public function getUpdate($request, $response, $args)
	{

		$id		 = $args['id'];
		$user	 = User::find($id);
		$groups	 = Group::all();
		$filials = Filial::all();
		if ($user)
		{
			return $this->view->render($response, 'user_manager/user/update.twig', [
						'user'		 => $user,
						'groups'	 => $groups,
						'filials'	 => $filials
			]);
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
				'filial_id'	 => v::noWhitespace()->notEmpty(),
			]);

			if (!$validation->failed())
			{
				$user->name		 = $request->getParam('name');
				$user->family	 = $request->getParam('family');
				$user->surname	 = $request->getParam('surname');
				$user->short	 = $request->getParam('short');

				$user->work_phone	 = $request->getParam('work_phone');
				$user->home_phone	 = $request->getParam('home_phone');
				$user->email		 = $request->getParam('email');

				$user->filial_id = $request->getParam('filial_id');
				if ($request->getParam('password'))
				{
					$user->password = password_hash($request->getParam('password'), PASSWORD_DEFAULT);
				}
				$user->save();
				$this->photo_upload($request, $user);

				$this->flash->addMessage('info', 'Пользователь ' . $user->login . ' изменен');
				return $response->withRedirect($this->router->pathFor('user_manager.user'));
			}

			return $response->withRedirect($this->router->pathFor('user_manager.user.update', ['id' => $user->id]));
		}

		$status = 404;
		return $response->withStatus($status);
	}

	public function postDelete($request, $response)
	{

		$id		 = $request->getParam('id');
		$user	 = User::find($id);
		if ($user)
		{
			$user_count = User::all()->count();
			if ($user_count == 1)
			{
				print json_encode(['err' => true, 'msg' => $this->lang['user']['del']['m1']]);
			}
			elseif ($user->id == $this->Auth->user()->id)
			{
				print json_encode(['err' => true, 'msg' => $this->lang['user']['del']['m2']]);
			}
			else
			{
				$user->delete();
				$old = $user->photo();
				if ($old)
				{
					unlink('.' . $old);
				}
				print json_encode(['err' => false, 'msg' => $this->lang['user']['del']['m3']]);
			}
//			return $response->withRedirect($this->router->pathFor('users'));
		}
		else
		{
			print json_encode(['err' => true, 'msg' => $this->lang['user']['del']['m4']]);
		}
	}

	private function photo_upload($request, $user)
	{

		$uploadedFiles	 = $request->getUploadedFiles();
		$uploadedFile	 = $uploadedFiles['photo'];
		if ($uploadedFile->getError() === UPLOAD_ERR_OK)
		{
			$old = $user->photo();
			if ($old)
			{
				unlink('.' . $old);
			}

			$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
			if (in_array($extension, ['jpg', 'jpeg', 'png']))
			{
				$uploadedFile->moveTo('./img/users/' . $user->id . '.' . $extension);
			}
		}
	}

}
