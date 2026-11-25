<?php

namespace App\Frontend\Controller;
use \App\Utilities\StaticHelpers;
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
        $entriesCount = $this->bookRepository->getAvailableBooksCount();
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        $results = $this->bookRepository->fetchAllAvailableBooksByPage($page, RESULTS_DISPLAYED_ON_PAGE);

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => "?" . http_build_query(['page' => max(1, $page - 1)]),
            'nextHref' => "?" . http_build_query(['page' => min($page + 1, $totalPages)])
        ]);

        if(empty($results))
        {
            echo "No results found";
        }

        $this->displayPage("index", array_merge(
            $paginationArray,
            array(
                'results' => $results
            )
        ));
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