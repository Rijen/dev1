<?php

namespace App\Controllers;

use App\Controllers\Controller;

class MainController extends Controller {

  public function index($request, $response) {

	$this->view->render($response, 'index.twig');
  }

}
