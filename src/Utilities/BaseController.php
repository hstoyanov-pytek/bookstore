<?php

namespace App\Utilities;

use \App\Utilities\AuthService;

abstract class BaseController
{
    public function __construct(
        protected \App\Repository\BookRepository $repository
    ){}

    public static function displayLoginNav() : bool
    {
        AuthService::ensureSessionUnique();
        if(!empty($_SESSION['userId']))
        {
            return false;
        }

        return true;
    }

    public function hasAuthority(string $role) : bool
    {
       AuthService::ensureSessionUnique();
       if(!empty($_SESSION['role']) && $_SESSION['role'] === $role)
           return true;

       return false;
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
        $authors = $this->repository->fetchAllAuthors();
        $this->displayNavigation("authors", [
            'authors' => $authors
        ]);
    }

    public function displayAllGenres() : void
    {
        $genres = $this->repository->fetchAllGenres();

        $this->displayNavigation("genres", [
            'genres' => $genres
        ]);
    }

    public function displayAllLanguages() : void
    {
        $languages = $this->repository->fetchAllLanguages();
        $this->displayNavigation("languages", [
            'languages' => $languages
        ]);
    }

    public function displayAllPublishers() : void
    {
        $publishers = $this->repository->fetchAllPublishers();
        $this->displayNavigation("publishers", [
            'publishers' => $publishers
        ]);
    }

}