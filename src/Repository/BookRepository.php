<?php

namespace App\Repository;
use \App\Model\BookModel;
use \App\Model\AuthorModel;
use \App\Model\PublisherModel;
use \App\Model\GenresModel;
use \App\Model\LanguagesModel;
use \App\Model\BookDetailsDTO;

class BookRepository
{
    public function  __construct(
        private \App\Utilities\Database\Database $database
    ){}


    private function updateTable(string $selectColumn, $selectValue, string $selectTableName, string $selectModelClass,
                                 string $whereUpdateColumn, $whereUpdateValue, string $updateTable, string $updateColumn) : void
    {
/// Templatised version of:
//        $whereLanguage = $this->database->createParams(cols:['name'], values:[$detailsDTO->book_languages], operators:['=']);
//        $languageResult = $this->database::$select
//            ->select("id")
//            ->from("languages")
//            ->where($whereLanguage)
//            ->getResult(LanguagesModel::class);
//
//        $whereUpdateBook_Languages = $this->database->createParams(cols:['book_id'], values:[$id], operators:['=']);
//        $this->database::$update
//            ->update("book_languages")
//            ->set(array(['col' => 'languages_id', 'value' => (int)$languageResult->id]))
//            ->where($whereUpdateBook_Languages)
//            ->exec();
        $whereSelect = $this->database->createParams(cols:[$selectColumn], values:[$selectValue], operators:['=']);
        $selectResult = $this->database::$select
            ->select("id")
            ->from($selectTableName)
            ->where($whereSelect)
            ->getResult($selectModelClass);

        $whereUpdate = $this->database->createParams(cols:[$whereUpdateColumn], values:[$whereUpdateValue], operators:['=']);
        $this->database::$update
            ->update($updateTable)
            ->set(array(['col' => $updateColumn, 'value' => $selectResult->id]))
            ->where($whereUpdate)
            ->exec();
    }

    /// RETURNS entry if it doesn't exist it creates it and returns it
    private function getEntry(string $table, array $whereParams, string $modelClass, array $insertCols = array(), array $insertVals = array()) : object
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

    public function fetchByPage(int $page, int $amountResults) : array
    {
//        /SELECT
//        book.id as book_id,
//        book.title as book_title,
//        book.year_published as year_published,
//        book.available as book_available,
//        book.cover_name as book_cover_name,
//        publisher.name as publisher,
//        author.name as book_author,
//        genres.name as book_genres,
//        languages.name as book_languages
//
//        FROM book
//        INNER JOIN publisher ON book.publisher_id = publisher.id
//
//        INNER JOIN book_author ON book.id = book_author.book_id
//        INNER JOIN author ON book_author.author_id = author.id
//
//        INNER JOIN book_genres ON book.id = book_genres.book_id
//        INNER JOIN genres ON book_genres.genres_id = genres.id
//
//        INNER JOIN book_languages ON book.id = book_languages.book_id
//        INNER JOIN languages ON book_languages.languages_id = languages.id
//        ORDER BY book.id
//        LIMIT 5 OFFSET 0;

        $limit = $amountResults;
        $offset = ($page - 1) * $limit;

        $results = $this->database::$select
            ->select("
                book.id as book_id,
                book.title as book_title,
                book.year_published as year_published,
                book.available as book_available,
                book.cover_name as book_cover_name,
                publisher.name as publisher,
                author.name as book_author,
                genres.name as book_genres,
                languages.name as book_languages
            ")
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")
            ->orderBy(['book.id'])
            ->limit($limit, $offset)
            ->getResults(BookDetailsDTO::class);

        return $results;
    }

    public function fetchAll() : array
    {
        $result = $this->database::$select
            ->select("
                book.id as book_id,
                book.title as book_title,
                book.year_published as year_published,
                book.available as book_available,
                book.cover_name as book_cover_name,
                publisher.name as publisher,
                author.name as book_author,
                genres.name as book_genres,
                languages.name as book_languages
            ")
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")
            ->orderBy(['book.id'])
            ->getResults(BookDetailsDTO::class);

        return $result;
    }

    public function fetchAllAvailable() : array
    {
        $whereAvailable = $this->database->createParams(cols: ['available'], values:['1'], operators:['=']);

        return $this->database::$select
            ->select("")
            ->from("book")
            ->where($whereAvailable)
            ->getResults(BookModel::class);
    }

    public function fetchAvailableByPage(int $page, int $amountResults) : array
    {
        $whereAvailable = $this->database->createParams(cols: ['available'], values:['1'], operators:['=']);

        $limit = $amountResults;
        $offset = ($page - 1) * $limit;

        $results = $this->database::$select
            ->select("
                book.id as book_id,
                book.title as book_title,
                book.year_published as year_published,
                book.available as book_available,
                book.cover_name as book_cover_name,
                publisher.name as publisher,
                author.name as book_author,
                genres.name as book_genres,
                languages.name as book_languages
            ")
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")
            ->where($whereAvailable)
            ->orderBy(['book.id'])
            ->limit($limit, $offset)
            ->getResults(BookDetailsDTO::class);

        return $results;
    }

    public function fetchBookByName(string $title) : null|BookDetailsDTO
    {
        $whereBook = $this->database->createParams(cols:['book.title'], alias:['bookTitle'], values:[$title], operators:["="]);

        $result = $this->database::$select
            ->select("
                book.id as book_id,
                book.title as book_title,
                book.year_published as year_published,
                book.available as book_available,
                publisher.name as publisher,
                author.name as book_author,
                genres.name as book_genres,
                languages.name as book_languages
            ")
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")

            ->where($whereBook)
            ->getResult(BookDetailsDTO::class);

        return $result;
    }

    public function fetchBookById(int $id) : null|BookDetailsDTO
    {
        $whereBook = $this->database->createParams(cols: ['book.id'], alias:['book_id'], values:[$id], operators:['=']);
//        SELECT book.title as book_title,
//        publisher.name as publisher,
//        book.year_published as year_published,
//        author.name as author,
//        genres.name as genre,
//        languages.name as language
//
//        FROM book
//        INNER JOIN publisher ON book.publisher_id = publisher.id
//
//        INNER JOIN book_author ON book.id = book_author.book_id
//        INNER JOIN author ON book_author.author_id = author.id
//
//        INNER JOIN book_genres ON book.id = book_genres.book_id
//        INNER JOIN genres ON book_genres.genres_id = genres.id
//
//        INNER JOIN book_languages ON book.id = book_languages.book_id
//        INNER JOIN languages ON book_languages.languages_id = languages.id;

        $result = $this->database::$select
            ->select("
                book.id as book_id,
                book.title as book_title,
                book.year_published as year_published,
                book.available as book_available,
                book.cover_name as book_cover_name,
                publisher.name as publisher,
                author.name as book_author,
                genres.name as book_genres,
                languages.name as book_languages
            ")
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")

            ->where($whereBook)
            ->getResult(BookDetailsDTO::class);


        if(!empty($result))
        {
            return $result;
        }

        return null;
    }

    public function updateById(int $id, BookDetailsDTO $detailsDTO) : void
    {
        $whereBookUpdate = $this->database->createParams(cols: ['id'], values:[$id], operators:['=']);

        if(empty($detailsDTO))
            throw new \Exception("BOOK DETAILS DTO is null");

        $wherePublisherUpdate = $this->database->createParams(cols:['name'], values:[$detailsDTO->publisher], operators: ['=']);
        $publisherResult = $this->database::$select
            ->select("id")
            ->from("publisher")
            ->where($wherePublisherUpdate)
            ->getResult(PublisherModel::class);

        $bookCols = array(
            ['col' => 'title', 'value'=> $detailsDTO->book_title],
            ['col' => 'year_published', 'value' => $detailsDTO->year_published],
            ['col' => 'available', 'value' => (int)$detailsDTO->book_available],
            ['col' => 'publisher_id', 'value'=>$publisherResult->id],
            ['col' => 'cover_name', 'value'=>empty($detailsDTO->book_cover_name) ? BOOK_COVER_DEFAULT : $detailsDTO->book_cover_name]
        );

        $this->database::$update
            ->update("book")
            ->set($bookCols)
            ->where($whereBookUpdate)
            ->exec();


        $this->updateTable(
            selectTableName: "languages",
            selectColumn:  "name",
            selectValue: $detailsDTO->book_languages,
            selectModelClass: LanguagesModel::class,
            whereUpdateColumn: "book_id",
            whereUpdateValue: $id,
            updateTable: "book_languages",
            updateColumn: "languages_id",
        );

        $this->updateTable(
            selectTableName: "author",
            selectColumn: "name",
            selectValue: $detailsDTO->book_author,
            selectModelClass: AuthorModel::class,
            whereUpdateColumn: 'book_id',
            whereUpdateValue: $id,
            updateTable: "book_author",
            updateColumn: "author_id"
        );

        $this->updateTable(
            selectTableName: "genres",
            selectColumn: "name",
            selectValue: $detailsDTO->book_genres,
            selectModelClass: GenresModel::class,
            whereUpdateColumn: 'book_id',
            whereUpdateValue: $id,
            updateTable: "book_genres",
            updateColumn: "genres_id"
        );

    }

    public function createEntry(array $newData) : void
    {
        extract($newData);

        $whereAuthor = $this->database->createParams(cols:['name'], values:[$author], operators:['=']);
        $authorEntry = $this->getEntry("author", $whereAuthor, AuthorModel::class, insertCols: array('name'), insertVals: array($author));

        $wherePublisher = $this->database->createParams(cols: ['name'], values: [$publisher], operators: ['=']);
        $publisherEntry = $this->getEntry("publisher", $wherePublisher, PublisherModel::class, insertCols: array('name'), insertVals: array($publisher));

        $whereGenre = $this->database->createParams(cols: ['name'], values:[$genre], operators:['=']);
        $genreEntry = $this->getEntry('genres', $whereGenre, GenresModel::class, insertCols: array('name'), insertVals: array($genre));

        $whereLanguage = $this->database->createParams(cols: ['name'], values: [$language], operators:['=']);
        $languageEntry = $this->getEntry('languages', $whereLanguage, LanguagesModel::class, insertCols: array('name'), insertVals: array($language));

        $this->database::$insert
            ->insertInto("book", ['title', 'publisher_id', 'year_published', 'cover_name'])
            ->values(array($title, $publisherEntry->id, $year_published, empty($cover_name) ? BOOK_COVER_DEFAULT : $cover_name))
            ->exec();

        $bookWhere = $this->database->createParams(cols: ['title', 'publisher_id'], values: [$title, $publisherEntry->id], operators:['=', '=']);
        $book = $this->database::$select->select('id')->from("book")->where($bookWhere)->getResult(BookModel::class);

        $this->database::$insert->insertInto("book_author", ['book_id', 'author_id'])->values(array($book->id , $authorEntry->id))->exec();
        $this->database::$insert->insertInto("book_genres", ['book_id', 'genres_id'])->values(array($book->id, $genreEntry->id))->exec();
        $this->database::$insert->insertInto("book_languages", ['book_id', 'languages_id'])->values(array($book->id, $languageEntry->id))->exec();
    }

    public function switchAvailabilityState(int $id) : void
    {
        $whereParams = $this->database->createParams(['id'],[$id],['=']);

        $currentState = $this->database::$select
            ->select(["available"])
            ->from("book")
            ->where($whereParams)
            ->getResult(BookModel::class);

        $this->database::$update
            ->update("book")
            ->set(array(['col' => 'available', 'value'=>(int)!$currentState->available]))
            ->where($whereParams)
            ->exec();
    }

    public function deleteBookEntry(int $id, bool $softDelete = true) : void
    {
        $whereParams = $this->database->createParams(['id'], [$id], ['=']);
        if($softDelete)
        {
            $this->database::$update
                ->update("book")
                ->set(array(['col' => "available", 'value' => 0]))
                ->where($whereParams)
                ->exec();
        }
        else
        {
            $this->database::$delete
                ->delete()
                ->from("book")
                ->where($whereParams)
                ->exec();
        }
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

    public function fetchAllPublishers() : array
    {
        return $this->database::$select->select("name")->from("publisher")->getResults(PublisherModel::class);
    }
}