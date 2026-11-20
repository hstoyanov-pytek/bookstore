<?php

namespace App\Frontend\Controller;
use \App\Model\BookDetailsDTO;

class PagesController extends BaseFrontendController
{
    public function __construct(\App\Repository\BookRepository $repository)
    {
        parent::__construct($repository);
    }

    public function displayLandingPage() : void
    {
        // $page = @(int)($_GET['page']) ?? 1; // This returns 0 when $_GET is null. Null cast to int = 0

        $page = empty($_GET['page']) ? 1 : @(int)($_GET['page']);
        $results = $this->repository->fetchAvailableByPage($page, RESULTS_DISPLAYED_ON_PAGE);

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
        $result = $this->repository->fetchBookById($id);

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