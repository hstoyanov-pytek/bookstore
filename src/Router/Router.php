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

    public function add(string $method, string $path, array $controller)
    {
        $this->normalizePath($path);
        $this->routes[] = array(
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
        );
    }

    public function dispatch(string $path)
    {
        $this->normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes as $route)
        {
            if(!preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method)
            {
                continue;
            }

            [$controller, $function] = $route['controller'];

            if($controller instanceof \App\Frontend\Controller\BaseController)
                $controller->{$function}();
            else
                throw new \Exception("{$controller} is not an instance of FRONTEND BASE CONTROLLER");
        }
    }
}