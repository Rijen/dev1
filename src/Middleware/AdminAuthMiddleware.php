<?php

namespace App\Middleware;


class AdminAuthMiddleware extends Middleware {

  public function __invoke($request, $response, $next) {

	if (!$this->container->adminAuth->check()) {
	  return $response->withRedirect($this->container->router->pathFor('admin.login'));
	}

	return $next($request, $response);
  }

}
