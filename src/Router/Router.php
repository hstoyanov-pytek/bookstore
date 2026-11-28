<?php
declare(strict_types=1);

namespace App\Router;

class Router
{
    private array $routes = array();


    private function normalizePath(string &$path) : void
    {
        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#/{2,}#' , '/', $path);
    }

    public function add(string $method, string $path, array $controller) : void
    {
        $this->normalizePath($path);
        $this->routes[] = array(
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
        );
    }

    public function dispatch(string $path) : void
    {
        $this->normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes as $route)
        {
            [$controller, $function] = $route['controller'];

            if(!preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method)
            {
                continue;
            }

            if(    $controller instanceof \App\Frontend\Controller\BaseFrontendController
                || $controller instanceof \App\Gateway\Controller\BaseGatewayController
                || $controller instanceof \App\Admin\Controller\BaseAdminController)
            {
                $controller->{$function}();
            }
            else
            {
                throw new \Exception("{$controller} is not an instance of a BASE CONTROLLER");
            }
        }
    }
}