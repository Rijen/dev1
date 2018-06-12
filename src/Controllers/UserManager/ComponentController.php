<?php

namespace App\Controllers\UserManager;

use App\Controllers\Controller;
use App\Models\Component;

class ComponentController extends Controller
{

	public function index($request, $response)
	{
		$components = Component::all();
		$this->view->render($response, 'user_manager/component/list.twig', ['components' => $components]);
	}

}
