<?php

namespace App\Repository;
use \App\Model\BookModel;
use \App\Model\AuthorModel;
use \App\Model\PublisherModel;
use \App\Model\GenresModel;
use \App\Model\LanguagesModel;

class BookRepository
{
    public function  __construct(
        private \App\Utilities\Database\Database $database
    ){}

    public function fetchAll() : array
    {
        $result = $this->database::$select->select("")->from("book")->orderBy("id")->getResults(BookModel::class);
        return $result;
    }

    public function fetchByName(string $title) : array
    {
        $result = $this->database::$select->select("")->from("book")->where([
            'col' => "title",
            'value'=> $title,
            'operator' => "="
        ])->getResults();

        return $result;
    }

    public function fetchBookById(int $id) : null|array|BookModel
    {
        $whereBook = $this->database->createParams(['id'],[$id],['=']);

        $result = $this->database::$select->select("")->from("book")->where($whereBook)->getResult(BookModel::class);
        //SELECT book.title as book_title, publisher.name as publisher_name, book.year_published as year_published FROM book INNER JOIN publisher ON book.publisher_id = publisher.id;

        if(!empty($result))
        {
            return $result;
        }

        return null;
    }

    public function updateById(int $id, array $newData)
    {


        $whereParams = $this->database->createParams(['id'],[$id],['=']);
        $this->database::$update->update("book")->set($newData)->where($whereParams)->exec();
    }

    public function createEntry(array $newData)
    {
        extract($newData);

        $whereAuthor = $this->database->createParams(['name'],[$author],['=']);
        $authorEntry = $this->getEntry("author", $whereAuthor, AuthorModel::class, insertCols: array('name'), insertVals: array($author));

        $wherePublisher = $this->database->createParams(['name'], [$publisher], ['=']);
        $publisherEntry = $this->getEntry("publisher", $wherePublisher, PublisherModel::class, insertCols: array('name'), insertVals: array($publisher));

        $whereGenre = $this->database->createParams(['name'],[$genre],['=']);
        $genreEntry = $this->getEntry('genres', $whereGenre, GenresModel::class, insertCols: array('name'), insertVals: array($genre));

        $whereLanguage = $this->database->createParams(['name'],[$language],['=']);
        $languageEntry = $this->getEntry('languages', $whereLanguage, LanguagesModel::class, insertCols: array('name'), insertVals: array($language));

        $this->database::$insert->insertInto("book", ['title', 'publisher_id', 'year_published'])->values(array($title, $publisherEntry->id, $year_published))->exec();

        $bookWhere = $this->database->createParams(['title', 'publisher_id'],[$title, $publisherEntry->id],['=', '=']);
        $book = $this->database::$select->select('id')->from("book")->where($bookWhere)->getResult(BookModel::class);

        $this->database::$insert->insertInto("book_author", ['book_id', 'author_id'])->values(array($book->id , $authorEntry->id))->exec();
        $this->database::$insert->insertInto("book_genres", ['book_id', 'genres_id'])->values(array($book->id, $genreEntry->id))->exec();
        $this->database::$insert->insertInto("book_languages", ['book_id', 'languages_id'])->values(array($book->id, $languageEntry->id))->exec();
    }

    public function deleteEntry(int $id)
    {
        $whereParams = $this->database->createParams(['id'], [$id], ['=']);
        $this->database::$delete->delete()->from("book")->where($whereParams)->exec();
    }

    public function fetchAllAuthors() : array
    {
        return $this->database::$select->select("")->from("author")->getResults(AuthorModel::class);
    }

    public function fetchAllGenres() : array
    {
        return $this->database::$select->select("")->from("genres")->getResults(GenresModel::class);
    }

    public function fetchAllLanguages() : array
    {
        return $this->database::$select->select("")->from("languages")->getResults(LanguagesModel::class);
    }

    /// RETURNS entry if it doesn't exist it creates it and returns it
    public function getEntry(string $table, array $whereParams, string $modelClass, array $insertCols = array(), array $insertVals = array()) : object
    {
        $query = $this->database::$select->select("")->from($table)->where($whereParams);
        var_dump($query);
        $foundEntry = $query->getResult($modelClass);

        if(empty($foundEntry) && !empty($insertCols) && !empty($insertVals))
        {
            $this->database::$insert->insertInto($table, $insertCols)->values($insertVals)->exec();

            return $this->getEntry($table, $whereParams, $modelClass);
        }

        return $foundEntry;
    }
}