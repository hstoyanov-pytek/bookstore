<?php

namespace App\Admin\Controller;

class BaseAdminController extends \App\Utilities\BaseController
{
    protected array $filterAuthors = array();
    protected array $filterPublishers = array();
    protected array $filterLanguages = array();
    protected array $filterGenres = array();

    public function __construct(
        \App\Repository\BookRepository $bookRepository,
        \App\Utilities\AuthService $authService
    )
    {
        parent::__construct($bookRepository, $authService);
        $this->showFilters = true;
    }

    #[\Override]
    protected function render(string $view, array $params = array()) : void
    {
        parent::prepareFilters($this->filterAuthors, $this->filterLanguages, $this->filterPublishers, $this->filterGenres);

        extract($params);

        ob_start();

        require_once __DIR__ . '/../../../views/admin/' . $view . '.view.php';
        $displayLoginNav = parent::displayLoginNav();

        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/admin/layouts/main.view.php';
    }
}