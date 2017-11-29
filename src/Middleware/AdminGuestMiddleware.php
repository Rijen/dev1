<?php

namespace App\Middleware;

class AdminGuestMiddleware extends Middleware {

  public function __invoke($request, $response, $next) {

	if ($this->container->adminAuth->check()) {
	  return $response->withRedirect($this->container->router->pathFor('admin.main'));
	}

	return $next($request, $response);
  }

}
