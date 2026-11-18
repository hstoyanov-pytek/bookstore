<?php

namespace App\Frontend\Controller;
use \App\Model\BookDetailsDTO;

class PagesController extends BaseController
{
    public function __construct(
        protected \App\Repository\BookRepository $repository
    ){}

    public function displayLandingPage()
    {
        $results = $this->repository->fetchAll();
        $availableResults = $this->repository->fetchAllAvailable();
        if(empty($results))
        {
            echo "No results found";
        }

        $this->displayPage("index", [
            'results' => $results,
            'availableResults' => $availableResults
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

            $details = new BookDetailsDTO();
            $details->arrayToObject($values);
            $details->book_id = $id;

            $this->repository->updateById($id, $details);
            header("Location: index.php");
        }

        $result = $this->repository->fetchBookById($id);
        $authors = $this->repository->fetchAllAuthors();
        $genres = $this->repository->fetchAllGenres();
        $languages = $this->repository->fetchAllLanguages();
        $publishers = $this->repository->fetchAllPublishers();

        if(empty($result))
        {
            echo "No books found with id: {$id}";
            return;
        }

        $this->displayPage("editBook", [
            'result' => $result,
            'authors' => $authors,
            'genres' => $genres,
            'languages' => $languages,
            'publishers' => $publishers
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
            $this->repository->deleteBookEntry($id);
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