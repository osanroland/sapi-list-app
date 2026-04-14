<?php

namespace App\Router;

use App\Controller\ListController;

class Router
{
    public function dispatch(string $uri, ListController $controller): void
    {
        if (preg_match('#^/list/(\d+)/subscribers$#', $uri, $m)) {
            $controller->subscribers((int) $m[1]);
        } else {
            $controller->index();
        }
    }
}
