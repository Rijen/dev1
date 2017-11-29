<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

class MainController extends Controller {

  public function index($request, $response) {

	$this->view->render($response, 'admin/index.twig');
  }

}
