<?php

namespace App\Frontend\Controller;

class BaseController
{
    protected function render(string $view, array $params)
    {
        extract($params);

        ob_start();
        require_once __DIR__ . '/../../../views/frontend/' . $view . '.view.php';

        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/frontend/layouts/main.view.php';
    }
}