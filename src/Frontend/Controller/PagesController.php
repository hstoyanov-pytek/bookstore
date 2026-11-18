<?php

namespace App\Frontend\Controller;

class PagesController extends BaseController
{
    public function __construct(
        protected \App\Repository\BookRepository $repository
    ){}

    public function displayLandingPage()
    {
        $results = $this->repository->fetchAll();
        if(empty($results))
        {
            echo "No results found";
        }

        $this->displayPage("index", [
            'results' => $results
        ]);
    }

    public function displayBookPage(int $id)
    {
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

    private function displayPage(string $pageKey, array $params = array())
    {
        $this->render('pages/'. $pageKey, $params);
    }

    public function editBookEntry(int $id)
    {
        if(!empty($_POST))
        {
            $values = array();
            foreach ($_POST as $name => $var)
            {
                $values[$name] = @(string)($var);
            }
            if(empty($_POST['available']))
            {
                $values['available'] = false;
            }

            $params = [
                'values' => $values
            ];

            $this->repository->updateById($id, $params);
            header("Location: index.php");
        }

        $result = $this->repository->fetchBookById($id);
        $authors = $this->repository->fetchAllAuthors();
        $genres = $this->repository->fetchAllGenres();
        $languages = $this->repository->fetchAllLanguages();

        if(empty($result))
        {
            echo "No books found with id: {$id}";
            return;
        }

        $this->displayPage("editBook", [
            'result' => $result,
            'authors' => $authors,
            'genres' => $genres,
            'languages' => $languages
        ]);
    }

    public function createBookEntry()
    {
        if(!empty($_POST))
        {
            $entryArray = array();

            foreach ($_POST as $key => $var)
            {
                $entryArray[$key] = @(string)$var;
            }

            $this->repository->createEntry($entryArray);
            header("Location: index.php");
        }

        $authors = $this->repository->fetchAllAuthors();
        $genres = $this->repository->fetchAllGenres();
        $languages = $this->repository->fetchAllLanguages();

        $this->displayPage('createBook', [
            'authors' => $authors,
            'genres' => $genres,
            'languages' => $languages
        ]);
    }

    public function deleteBookEntryById(int $id)
    {
        if(!empty($id))
        {
            $this->repository->deleteEntry($id);
        }

        $results = $this->repository->fetchAll();
        if(empty($results))
        {
            echo "No results found";
        }

        $this->displayPage('index',[
            'results' => $results
        ]);
    }
}