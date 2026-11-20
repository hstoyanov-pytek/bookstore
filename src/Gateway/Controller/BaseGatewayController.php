<?php

namespace App\Gateway\Controller;

class BaseGatewayController extends \App\Utilities\BaseController
{
    protected function render(string $view, array $params) : void
    {
        extract($params);

        ob_start();
        require_once __DIR__ . '/../../../views/frontend/gateway/' . $view . '.view.php';
        $displayLoginNav = parent::DisplayLoginNav();
        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/frontend/layouts/main.view.php';
    }
}