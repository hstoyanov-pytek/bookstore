<?php

namespace App\Editor\Controller;

class BaseEditorController extends \App\Utilities\BaseController
{
    public function __construct(\App\Repository\BookRepository $bookRepository,
                                \App\Utilities\AuthService $authService)
    {
        parent::__construct($bookRepository, $authService);
    }

    #[\Override]
    protected function render(string $view, array $params = array()) : void
    {
        extract($params);

        ob_start();

        require_once __DIR__ . '/../../../views/editor/' . $view . '.view.php';
        $displayLoginNav = parent::displayLoginNav();

        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/editor/layouts/main.view.php';
    }
}