<?php

namespace App\Admin\Controller;

class BaseAdminController extends \App\Utilities\BaseController
{
    protected function render(string $view, array $params) : void
    {
        extract($params);

        ob_start();
        require_once __DIR__ . '/../../../views/admin/' . $view . '.view.php';
        $displayLoginNav = parent::DisplayLoginNav();

        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/admin/layouts/main.view.php';
    }
}