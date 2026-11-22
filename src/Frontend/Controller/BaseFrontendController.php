<?php

namespace App\Frontend\Controller;

class BaseFrontendController extends \App\Utilities\BaseController
{
    public function __construct(\App\Repository\BookRepository $repository,
                                \App\Utilities\AuthService $authService)
    {
        parent::__construct($repository, $authService);
    }

    #[\Override]
    protected function render(string $view, array $params = array()) : void
    {
        extract($params);

        ob_start();
        require_once __DIR__ . '/../../../views/frontend/' . $view . '.view.php';
        $displayLoginNav = parent::displayLoginNav();
        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/frontend/layouts/main.view.php';
    }
}