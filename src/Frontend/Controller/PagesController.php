<?php

namespace App\Frontend\Controller;
use \App\Model\BookDetailsDTO;

class PagesController extends BaseFrontendController
{
    public function __construct(\App\Repository\BookRepository $bookRepository,
                                \App\Utilities\AuthService $authService)
    {
        parent::__construct($bookRepository, $authService);
    }

    public function displayLandingPage() : void
    {
        $page = empty($_GET['page']) ? 1 : @(int)($_GET['page']);
        $results = $this->bookRepository->fetchAvailableByPage($page, RESULTS_DISPLAYED_ON_PAGE);

        if(empty($results))
        {
            echo "No results found";
        }

        $this->displayPage("index", [
            'page' => $page,
            'results' => $results,
        ]);
    }

    public function displayBookPage() : void
    {
        $id = @(int)($_GET['id'] ?? 1);
        $result = $this->bookRepository->fetchBookById($id);

        if(empty($result))
        {
            echo "No books found with id: {$id}";
            return;
        }

        $this->displayPage("book", [
            'result' => $result
        ]);
    }
}