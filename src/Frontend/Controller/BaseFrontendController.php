<?php

namespace App\Frontend\Controller;

class BaseFrontendController extends \App\Utilities\BaseController
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
        require_once __DIR__ . '/../../../views/frontend/' . $view . '.view.php';
        $displayLoginNav = parent::displayLoginNav();
        $contents = ob_get_clean();

        require_once __DIR__ . '/../../../views/frontend/layouts/main.view.php';
    }
}