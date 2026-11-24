<?php

namespace App\Utilities;

use \App\Utilities\AuthService;
use \App\Utilities\StaticHelpers;

abstract class BaseController
{
    public function __construct(
        protected \App\Repository\BookRepository $bookRepository,
        protected AuthService $authService
    ){}

    public function displayLoginNav() : bool
    {
        if($this->authService->isLoggedIn())
        {
            return false;
        }

        return true;
    }

    abstract protected function render(string $view, array $params = array()): void;

    protected function displayPage(string $pageKey, array $params = array()) : void
    {
        $this->render('pages/'. $pageKey, $params);
    }
    protected function displayNavigation(string $pageKey, array $params = array()) : void
    {
        $this->render('navigation/'. $pageKey, $params);
    }

    public function displayAllAuthors() : void
    {
        $author = @(string)($_GET['author']) ?? "";
        $page = @($_GET['page']) ?? 1;
        $entriesCount = $this->bookRepository->getAuthorsCount();
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        if(!empty($author))
        {
            $this->displayBooksByAuthor($author, $page, RESULTS_DISPLAYED_ON_PAGE);
            die();
        }
        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages,
            [
                'previousHref' => "/authors?" . http_build_query(['page' => max(1, $page - 1)]),
                'nextHref' =>  "/authors?" . http_build_query(['page' => min($page + 1, $totalPages)])
            ]);

        $authors = $this->bookRepository->fetchAllAuthors($page, RESULTS_DISPLAYED_ON_PAGE);
        $this->displayNavigation("authors", array_merge(
            $paginationArray,
            array('authors' => $authors)
        ));
    }
    public function displayBooksByAuthor(string $author, int $page, int $limit) : void
    {
        $entriesCount = $this->bookRepository->getAvailableBooksCountByAuthor($author);
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);
        $results = $this->bookRepository->fetchAvailableBooksByAuthor($author, $page, $limit);

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => "/authors?" . http_build_query(['page' => max(1, $page - 1), 'author' => $author]),
            'nextHref' =>  "/authors?" . http_build_query(['page' => min($page + 1, $totalPages), 'author' => $author])
        ]);

        $this->render("/../templates/booksGallery",
            array_merge(
                $paginationArray,
                [
                    'filter' => 'author',
                    'name' => $author,
                    'results' => $results
                ]
            ));
    }

    public function displayAllGenres() : void
    {
        $genre = @(string)($_GET['genre']) ?? "";
        $page = @($_GET['page']) ?? 1;
        $entriesCount = $this->bookRepository->getGenresCount();
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        if(!empty($genre))
        {
            $this->displayBooksByGenre($genre, $page, RESULTS_DISPLAYED_ON_PAGE);
            die();
        }

        $genres = $this->bookRepository->fetchAllGenres($page, RESULTS_DISPLAYED_ON_PAGE);

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages,
            [
            'previousHref' => "/genres?" . http_build_query(['page' => max(1, $page - 1)]),
            'nextHref' =>  "/genres?" . http_build_query(['page' => min($page + 1, $totalPages)])
        ]);
        $this->displayNavigation("genres", array_merge(
            $paginationArray,
            array(
                'genres' => $genres
            )
        ));
    }

    public function displayBooksByGenre(string $genre, int $page, int $limit) : void
    {
        $entriesCount = $this->bookRepository->getAvailableBooksCountByGenre($genre);
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);
        $results = $this->bookRepository->fetchAvailableBooksByGenre($genre, $page, $limit);

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => "/genres?" . http_build_query(['page' => max(1, $page - 1), 'genre' => $genre]),
            'nextHref' =>  "/genres?" . http_build_query(['page' => min($page + 1, $totalPages), 'genre' => $genre])
        ]);

        $this->render("/../templates/booksGallery",
        array_merge(
            $paginationArray,
            [
                'filter' => 'genre',
                'name' => $genre,
                'results' => $results
            ]
        ));
    }

    public function displayAllLanguages() : void
    {
        $languages = $this->bookRepository->fetchAllLanguages();
        $this->displayNavigation("languages", [
            'languages' => $languages
        ]);
    }

    public function displayAllPublishers() : void
    {
        $publishers = $this->bookRepository->fetchAllPublishers();
        $this->displayNavigation("publishers", [
            'publishers' => $publishers
        ]);
    }


}