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
            return;
        }

        $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages,
            [
                'previousHref' => $prefix . "/authors?" . http_build_query(['page' => max(1, $page - 1)]),
                'nextHref' =>  $prefix . "/authors?" . http_build_query(['page' => min($page + 1, $totalPages)])
            ]);

        $authors = $this->bookRepository->fetchAllAuthorsByPage($page, RESULTS_DISPLAYED_ON_PAGE);
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

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => $prefix . "/authors?" . http_build_query(['page' => max(1, $page - 1), 'author' => $author]),
            'nextHref' =>  $prefix . "/authors?" . http_build_query(['page' => min($page + 1, $totalPages), 'author' => $author])
        ]);

        $this->render("/../templates/booksGallery",
            array_merge(
                $paginationArray,
                array(
                    'filter' => 'author',
                    'name' => $author,
                    'results' => $results
                )
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
            return;
        }
        $genres = $this->bookRepository->fetchAllGenresByPage($page, RESULTS_DISPLAYED_ON_PAGE);

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages,
            [
            'previousHref' => $prefix . "/genres?" . http_build_query(['page' => max(1, $page - 1)]),
            'nextHref' =>  $prefix ."/genres?" . http_build_query(['page' => min($page + 1, $totalPages)])
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

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => $prefix . "/genres?" . http_build_query(['page' => max(1, $page - 1), 'genre' => $genre]),
            'nextHref' => $prefix . "/genres?" . http_build_query(['page' => min($page + 1, $totalPages), 'genre' => $genre])
        ]);

        $this->render("/../templates/booksGallery",
        array_merge(
            $paginationArray,
            array(
                'filter' => 'genre',
                'name' => $genre,
                'results' => $results
            )
        ));
    }

    public function displayAllLanguages() : void
    {
        $language = @(string)($_GET['language']) ?? "";
        $page = @($_GET['page']) ?? 1;
        $entriesCount = $this->bookRepository->getLanguagesCount();
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        if(!empty($language))
        {
            $this->displayBooksByLanguage($language, $page, RESULTS_DISPLAYED_ON_PAGE);
            return;
        }

        $languages = $this->bookRepository->fetchAllLanguagesByPage($page, RESULTS_DISPLAYED_ON_PAGE);

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages,
            [
                'previousHref' => $prefix . "/languages?" . http_build_query(['page' => max(1, $page - 1)]),
                'nextHref' =>  $prefix . "/languages?" . http_build_query(['page' => min($page + 1, $totalPages)])
            ]);

        $this->displayNavigation("languages", array_merge(
            $paginationArray,
            array(
                'languages' => $languages
            )
        ));
    }

    public function displayBooksByLanguage(string $language, int $page, int $limit) : void
    {
        $entriesCount = $this->bookRepository->getAvailableBooksCountByLanguage($language);
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        $results = $this->bookRepository->fetchAvailableBooksByLanguage($language, $page, $limit);

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => $prefix . "/languages?" . http_build_query(['page' => max(1, $page - 1), 'language' => $language]),
            'nextHref' =>  $prefix . "/languages?" . http_build_query(['page' => min($page + 1, $totalPages), 'language' => $language])
        ]);

        $this->render("../templates/booksGallery", array_merge(
            $paginationArray,
            array(
                'filter' => 'language',
                'name' => $language,
                'results' => $results
            )
        ));
    }

    public function displayAllPublishers() : void
    {
        $publisher = @(string)($_GET['publisher']) ?? "";
        $page = @($_GET['page']) ?? 1;
        $entriesCount = $this->bookRepository->getPublishersCount();
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);

        if(!empty($publisher))
        {
            $this->displayBooksByPublisher($publisher, $page, RESULTS_DISPLAYED_ON_PAGE);
            return;
        }

        $publishers = $this->bookRepository->fetchAllPublishersByPage($page, RESULTS_DISPLAYED_ON_PAGE);

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages,
            [
                'previousHref' => $prefix . "/publishers?" . http_build_query(['page' => max(1, $page - 1)]),
                'nextHref' => $prefix . "/publishers?" . http_build_query(['page' => min($page + 1, $totalPages)])
            ]);

        $this->displayNavigation("publishers", array_merge(
            $paginationArray,
            array(
                'publishers' => $publishers
            )
        ));
    }

    public function displayBooksByPublisher(string $publisher, int $page, int $limit) : void
    {
        $entriesCount = $this->bookRepository->getAvailableBooksCountByPublisher($publisher);
        $totalPages = ceil($entriesCount / RESULTS_DISPLAYED_ON_PAGE);
        $results = $this->bookRepository->fetchAvailableBooksByPublisher($publisher, $page, $limit);

       $prefix = @$_SESSION['permissions'] < 110  ? "" : "/admin";

        $paginationArray = StaticHelpers::createPaginationParams($page, $totalPages, [
            'previousHref' => $prefix . "/publishers?" . http_build_query(['page' => max(1, $page - 1), 'publisher' => $publisher]),
            'nextHref' =>  $prefix . "/publishers?" . http_build_query(['page' => min($page + 1, $totalPages), 'publisher' => $publisher])
        ]);

        $this->render("../templates/booksGallery", array_merge(
            $paginationArray,
            array(
                'filter' => 'publisher',
                'name' => $publisher,
                'results' => $results
            )
        ));
    }
}