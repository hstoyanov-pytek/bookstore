<?php

namespace App\Repository;
use \App\Model\BookModel;
use \App\Model\AuthorModel;
use \App\Model\PublisherModel;
use \App\Model\GenresModel;
use \App\Model\LanguagesModel;
use \App\Model\BookDetailsDTO;
use \App\Model\CountModel;
use App\Utilities\Database\SelectSpec;

class BookRepository
{
    public function  __construct(
        private \App\Utilities\Database\Database $database
    ){}


    /// RETURNS entry if it doesn't exist it creates it and returns it
    private function getEntry(string $table, array $whereParams, string $modelClass, array $insertCols = array(), array $insertVals = array()) : object
    {
        $query = $this->database::$select
            ->select()
            ->from($table)
            ->where($whereParams);
        var_dump($query);

        $foundEntry = $query->getResult($modelClass);

        if(empty($foundEntry) && !empty($insertCols) && !empty($insertVals))
        {
            $this->database::$insert->insertInto($table, $insertCols)->values($insertVals)->exec();

            return $this->getEntry($table, $whereParams, $modelClass);
        }

        return $foundEntry;
    }

    private function getBookWhere(array $params) : object
    {
        $selectParams = $this->database->createSelectParams(
            cols: [
                'book.id',
                'book.title',
                'book.year_published',
                'book.available',
                'book.cover_name',
                'publisher.name',
                'author.name',
                'genres.name',
                'languages.name'
            ],
            alias: [
                'book_id',
                'book_title',
                'year_published',
                'book_available',
                'book_cover_name',
                'publisher',
                'book_author',
                'book_genres',
                'book_languages'
            ]
        );
        $result = $this->database::$select
            ->select($selectParams)
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")
            ->where($params)
            ->getResult(BookDetailsDTO::class);

        return $result;
    }

    private function getBooksWhere(array $params, int $page, int $limit) : array
    {
        $offset = ($page - 1) * $limit;
        $selectParams = $this->database->createSelectParams(
            cols:[
                'book.id',
                'book.title',
                'book.year_published',
                'book.available',
                'book.cover_name',
                'publisher.name',
                'author.name',
                'genres.name',
                'languages.name',
            ],
            alias: [
                'book_id',
                'book_title',
                'year_published',
                'book_available',
                'book_cover_name',
                'publisher',
                'book_author',
                'book_genres',
                'book_languages'
            ]
        );
        $results = $this->database::$select
            ->select($selectParams)
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")
            ->where($params)
            ->limit($limit, $offset)
            ->getResults(BookDetailsDTO::class);

        return $results;
    }

    private function fetchAvailableByPageWhere(array $inWhereParams, int $page, int $limit) : array
    {
        $whereAvailable = $this->database->createParams(cols: ['available'], values:['1'], operators:['=']);
        $whereParams = array_merge($inWhereParams, $whereAvailable);

        $offset = ($page - 1) * $limit;

        $selectParams = $this->database->createSelectParams(
            cols: [
                'book.id',
                'book.title',
                'book.year_published',
                'book.available',
                'book.cover_name',
                'publisher.name',
                'author.name',
                'genres.name',
                'languages.name',
            ],
            alias: [
                'book_id',
                'book_title',
                'year_published',
                'book_available',
                'book_cover_name',
                'publisher',
                'book_author',
                'book_genres',
                'book_languages'
            ]
        );

        $results = $this->database::$select
            ->select($selectParams)
            ->from("book")
            ->innerJoin("publisher", "book.publisher_id = publisher.id")

            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->innerJoin("author", "book_author.author_id = author.id")

            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->innerJoin("genres", "book_genres.genres_id = genres.id")

            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->innerJoin("languages", "book_languages.languages_id = languages.id")
            ->where($whereParams)
            ->orderBy(['book.id'])
            ->limit($limit, $offset)
            ->getResults(BookDetailsDTO::class);

        return $results;
    }

    // ============================================= CRUD =================================================
    private function updateTable(string $selectColumn, $selectValue, string $selectTableName, string $selectModelClass,
                                 string $whereUpdateColumn, $whereUpdateValue, string $updateTable, string $updateColumn) : void
    {
        $whereSelect = $this->database->createParams(cols:[$selectColumn], values:[$selectValue], operators:['=']);
        $selectResult = $this->database::$select
            ->select(array(['cols' => 'id']))
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
    public function updateById(int $id, BookDetailsDTO $detailsDTO) : void
    {
        $whereBookUpdate = $this->database->createParams(cols: ['id'], values:[$id], operators:['=']);

        if(empty($detailsDTO))
            throw new \Exception("BOOK DETAILS DTO is null");

        $wherePublisherUpdate = $this->database->createParams(cols:['name'], values:[$detailsDTO->publisher], operators: ['=']);
        $publisherResult = $this->database::$select
            ->select(array(['cols' => 'id']))
            ->from("publisher")
            ->where($wherePublisherUpdate)
            ->getResult(PublisherModel::class);

        $bookCols = array(
            ['col' => 'title', 'value'=> $detailsDTO->book_title],
            ['col' => 'year_published', 'value' => $detailsDTO->year_published],
            ['col' => 'available', 'value' => (int)$detailsDTO->book_available],
            ['col' => 'publisher_id', 'value'=>$publisherResult->id],
        );

        if(!empty($detailsDTO->book_cover_name))
        {
            $bookCols[] = ['col' => 'cover_name', 'value'=> $detailsDTO->book_cover_name];
        }

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
        $book = $this->database::$select
            ->select(array(['cols' => 'id']))
            ->from("book")
            ->where($bookWhere)
            ->getResult(BookModel::class);

        $this->database::$insert->insertInto("book_author", ['book_id', 'author_id'])->values(array($book->id , $authorEntry->id))->exec();
        $this->database::$insert->insertInto("book_genres", ['book_id', 'genres_id'])->values(array($book->id, $genreEntry->id))->exec();
        $this->database::$insert->insertInto("book_languages", ['book_id', 'languages_id'])->values(array($book->id, $languageEntry->id))->exec();
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

    public function switchAvailabilityState(int $id) : void
    {
        $whereParams = $this->database->createParams(['id'],[$id],['=']);

        $currentState = $this->database::$select
            ->select(array(['cols' => 'available']))
            ->from("book")
            ->where($whereParams)
            ->getResult(BookModel::class);

        $this->database::$update
            ->update("book")
            ->set(array(['col' => 'available', 'value'=>(int)!$currentState->available]))
            ->where($whereParams)
            ->exec();
    }

    // ============================================= Genres =================================================
    public function fetchAllGenres() : array
    {
        return $this->database::$select
            ->select()
            ->from("genres")
            ->getResults(GenresModel::class);
    }

    public function fetchAllGenresByPage(int $page, int $limit) : array
    {
        $offset = ($page - 1) * $limit;

        return $this->database::$select
            ->select()
            ->from("genres")
            ->limit($limit, $offset)
            ->getResults(GenresModel::class);
    }

    public function fetchAvailableBooksByGenre(string $genre, int $page, int $limit) : array
    {
        $whereGenres = $this->database->createParams(cols: ['name'], values: [$genre], operators: ["="]);
        $genreFound = $this->database::$select
            ->select()
            ->from("genres")
            ->where($whereGenres)
            ->getResult(GenresModel::class);

        $whereParams = $this->database->createParams(cols: ['book_genres.genres_id'], alias:["genresId"], values:[$genreFound->id], operators:['=']);
        $results = $this->fetchAvailableByPageWhere($whereParams, $page, $limit);
        return $results;
    }

    public function getAvailableBooksCountByGenre(string $genre) : int
    {
        $whereGenre = $this->database->createParams(cols:['name'], values: [$genre], operators: ['=']);
        $foundGenre = $this->database::$select
            ->select()
            ->from("genres")
            ->where($whereGenre)
            ->getResult(GenresModel::class);

        $whereParams = $this->database->createParams(
          cols:["book.available", "book_genres.genres_id"],
            alias: ["available", "genres_id"],
            values: ["1", $foundGenre->id],
            operators: ["=", "="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function getAllBooksCountByGenre(string $genre) : int
    {
        $whereGenre = $this->database->createParams(cols:['name'], values: [$genre], operators: ['=']);
        $foundGenre = $this->database::$select
            ->select()
            ->from("genres")
            ->where($whereGenre)
            ->getResult(GenresModel::class);

        $whereParams = $this->database->createParams(
          cols:["book_genres.genres_id"],
            alias: ["genres_id"],
            values: [$foundGenre->id],
            operators: ["="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->innerJoin("book_genres", "book.id = book_genres.book_id")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function fetchBooksByGenre(string $genre, int $page, int $limit) : array
    {
        $whereGenre = $this->database->createParams(cols:["name"], values:[$genre], operators:["="]);
        $foundGenre = $this->database::$select
            ->select()
            ->from("genres")
            ->where($whereGenre)
            ->getResult(GenresModel::class);

        $whereParams = $this->database->createParams(cols:["genres.id"], alias:["genreId"], values:[$foundGenre->id], operators:["="]);

        $filteredResults = $this->getBooksWhere($whereParams, $page, $limit);

        return $filteredResults;
    }

    public function getGenresCount() : int
    {
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("genres")
            ->getResult(CountModel::class);

        return $result->count;
    }


    // ============================================= Authors =================================================

    public function fetchAllAuthors() : array
    {
        return $this->database::$select
            ->select()
            ->from("author")
            ->getResults(AuthorModel::class);
    }

    public function fetchAllAuthorsByPage(int $page, int $limit) : array
    {
        $offset = ($page - 1) * $limit;
        return $this->database::$select
            ->select()
            ->from("author")
            ->limit($limit, $offset)
            ->getResults(AuthorModel::class);
    }

    public function fetchAvailableBooksByAuthor(string $authorName, int $page, int $limit) : array
    {
        $whereAuthor = $this->database->createParams(cols:["name"],values:[$authorName],operators:["="]);
        $foundAuthor = $this->database::$select
            ->select()
            ->from("author")
            ->where($whereAuthor)
            ->getResult(AuthorModel::class);

        $whereParams = $this->database->createParams(cols:["book_author.author_id"],alias:["authorId"], values:[$foundAuthor->id],operators:["="]);
        $filteredResults = $this->fetchAvailableByPageWhere($whereParams, $page, $limit);

        return $filteredResults;
    }

    public function getAvailableBooksCountByAuthor(string $author) : int
    {
        $whereAuthor = $this->database->createParams(cols:["name"], values: [$author], operators: ["="]);
        $foundAuthor = $this->database::$select
            ->select()
            ->from("author")
            ->where($whereAuthor)
            ->getResult(AuthorModel::class);

        $whereParams = $this->database->createParams(
            cols:["book.available", "book_author.author_id"],
            alias: ["available", "authorId"],
            values: ["1", $foundAuthor->id],
            operators: ["=", "="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function getAllBooksCountByAuthor(string $author): int
    {
        $whereAuthor = $this->database->createParams(cols:["name"], values: [$author], operators: ["="]);
        $foundAuthor = $this->database::$select
            ->select()
            ->from("author")
            ->where($whereAuthor)
            ->getResult(AuthorModel::class);

        $whereParams = $this->database->createParams(
            cols:["book_author.author_id"],
            alias: ["authorId"],
            values: [$foundAuthor->id],
            operators: ["="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->innerJoin("book_author", "book.id = book_author.book_id")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;

    }

    public function fetchBooksByAuthor(string $author, int $page, int $limit) : array
    {
        $whereAuthor = $this->database->createParams(cols: ["name"], values:[$author], operators:["="]);
        $foundAuthor = $this->database::$select
            ->select()
            ->from("author")
            ->where($whereAuthor)
            ->getResult(AuthorModel::class);

        $whereParams = $this->database->createParams(cols:["author.id"], alias:["authorId"], values:[$foundAuthor->id], operators:["="]);
        $filteredResults = $this->getBooksWhere($whereParams, $page, $limit);

        return $filteredResults;
    }

    public function getAuthorsCount() : int
    {
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("author")
            ->getResult(CountModel::class);

        return $result->count;
    }

    // ============================================= Languages =================================================

    public function fetchAllLanguages() : array
    {
        return $this->database::$select
            ->select()
            ->from("languages")
            ->getResults(LanguagesModel::class);
    }

    public function fetchAllLanguagesByPage(int $page, int $limit) : array
    {
        $offset = ($page - 1) * $limit;
        return $this->database::$select
            ->select()
            ->from("languages")
            ->limit($limit, $offset)
            ->getResults(LanguagesModel::class);
    }

    public function fetchAvailableBooksByLanguage(string $language, int $page, int $limit) : array
    {
        $whereLanguage = $this->database->createParams(cols:["name"], values:[$language], operators:["="]);
        $foundLanguage = $this->database::$select
            ->select()
            ->from("languages")
            ->where($whereLanguage)
            ->getResult(LanguagesModel::class);

        $whereParams = $this->database->createParams(cols:["book_languages.languages_id"], alias:["languageId"], values:[$foundLanguage->id], operators:["="]);

        $filteredResult = $this->fetchAvailableByPageWhere($whereParams, $page, $limit);
        return $filteredResult;
    }

    public function getAvailableBooksCountByLanguage(string $language) : int
    {
        $whereLanguage = $this->database->createParams(cols:["name"], values:[$language], operators:["="]);
        $foundLanguage = $this->database::$select
            ->select()
            ->from("languages")
            ->where($whereLanguage)
            ->getResult(LanguagesModel::class);

        $whereParams = $this->database->createParams(
            cols:["book.available", "book_languages.languages_id"],
            alias: ["available", "languageId"],
            values: ["1", $foundLanguage->id],
            operators: ["=", "="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function getAllBooksCountByLanguage(string $language) : int
    {
        $whereLanguage = $this->database->createParams(cols:["name"], values:[$language], operators:["="]);
        $foundLanguage = $this->database::$select
            ->select()
            ->from("languages")
            ->where($whereLanguage)
            ->getResult(LanguagesModel::class);

        $whereParams = $this->database->createParams(
            cols:["book_languages.languages_id"],
            alias: ["languageId"],
            values: [$foundLanguage->id],
            operators: ["="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->innerJoin("book_languages", "book.id = book_languages.book_id")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function fetchBooksByLanguage(string $language, int $page, int $limit) : array
    {
        $whereLanguage = $this->database->createParams(cols:["name"], values:[$language], operators:["="]);
        $foundLanguage = $this->database::$select
            ->select()
            ->from("languages")
            ->where($whereLanguage)
            ->getResult(LanguagesModel::class);

        $whereParams = $this->database->createParams(cols:["languages.id"], alias:["languageId"], values:[$foundLanguage->id], operators:["="]);

        $filteredResults = $this->getBooksWhere($whereParams, $page, $limit);

        return $filteredResults;
    }

    public function getLanguagesCount() : int
    {
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("languages")
            ->getResult(CountModel::class);
        return $result->count;
    }

    // ============================================= Publishers =================================================

    public function fetchAllPublishers() : array
    {
        return $this->database::$select
            ->select(array(['cols'=> 'name']))
            ->from("publisher")
            ->getResults(PublisherModel::class);
    }

    public function fetchAllPublishersByPage(int $page, int $limit) : array
    {
        $offset = ($page - 1) * $limit;
        return $this->database::$select
            ->select(array(['cols'=> 'name']))
            ->from("publisher")
            ->limit($limit, $offset)
            ->getResults(PublisherModel::class);
    }

    public function fetchAvailableBooksByPublisher(string $publisher, int $page, int $limit) : array
    {
        $wherePublisher = $this->database->createParams(cols:["name"], values:[$publisher], operators:["="]);
        $foundPublisher = $this->database::$select
            ->select()
            ->from("publisher")
            ->where($wherePublisher)
            ->getResult(PublisherModel::class);

        $whereParams = $this->database->createParams(cols:["publisher.id"], alias:["publisherId"], values:[$foundPublisher->id], operators:["="]);

        $filteredResults = $this->fetchAvailableByPageWhere($whereParams, $page, $limit);

        return $filteredResults;
    }

    public function getAvailableBooksCountByPublisher(string $publisher) : int
    {
        $wherePublisher = $this->database->createParams(cols:["name"], values: [$publisher], operators: ["="]);

        $foundPublisher = $this->database::$select
            ->select()
            ->from("publisher")
            ->where($wherePublisher)
            ->getResult(PublisherModel::class);

        $whereParams = $this->database->createParams(
            cols:["book.available", "book.publisher_id"],
            alias: ["available", "publisherId"],
            values: ["1", $foundPublisher->id],
            operators: ["=", "="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function getAllBooksCountByPublisher(string $publisher) : int
    {
        $wherePublisher = $this->database->createParams(cols:["name"], values: [$publisher], operators: ["="]);

        $foundPublisher = $this->database::$select
            ->select()
            ->from("publisher")
            ->where($wherePublisher)
            ->getResult(PublisherModel::class);

        $whereParams = $this->database->createParams(
            cols:["book.publisher_id"],
            alias: ["publisherId"],
            values: [$foundPublisher->id],
            operators: ["="]
        );

        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function fetchBooksByPublisher(string $publisher, int $page, int $limit) : array
    {
        $wherePublisher = $this->database->createParams(cols: ["name"], values:[$publisher], operators:["="]);
        $foundPublisher = $this->database::$select
            ->select()
            ->from("publisher")
            ->where($wherePublisher)
            ->getResult(PublisherModel::class);

        $whereParams = $this->database->createParams(cols:["publisher_id"], alias: ["publisherId"], values:[$foundPublisher->id], operators:["="]);
        $filteredResults = $this->getBooksWhere($whereParams, $page, $limit);

        return $filteredResults;
    }

    public function getPublishersCount() : int
    {
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("publisher")
            ->getResult(CountModel::class);

        return $result->count;
    }

    // ============================================= All Books =================================================
    public function getAvailableBooksCount() : int
    {
        $whereParams = $this->database->createParams(cols: ["available"], values: ["1"], operators: ["="]);
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->where($whereParams)
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function getAllBooksCount() : int
    {
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->from("book")
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function fetchBookByName(string $title) : null|BookDetailsDTO
    {
        $whereBook = $this->database->createParams(cols:['book.title'], alias:['bookTitle'], values:[$title], operators:["="]);

        $selectParams = $this->database->createSelectParams(
            cols:[
                'book.id',
                'book.title',
                'book.year_published',
                'book.available',
                'publisher.name',
                'author.name',
                'genres.name',
                'languages.name'
            ],
            alias: [
                'book_id',
                'book_title',
                'year_published',
                'book_available',
                'publisher',
                'book_author',
                'book_genres',
                'book_languages'
            ]
        );

        $result = $this->database::$select
            ->select($selectParams)
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

        $result = $this->getBookWhere($whereBook);
        if(!empty($result))
        {
            return $result;
        }

        return null;
    }

    public function fetchAllByPage(int $page, int $amountResults) : array
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

        $selectParams = $this->database->createSelectParams(
            cols:[
                'book.id',
                'book.title',
                'book.year_published',
                'book.available',
                'book.cover_name',
                'publisher.name',
                'author.name',
                'genres.name',
                'languages.name'
            ],
            alias: [
                'book_id',
                'book_title',
                'year_published',
                'book_available',
                'book_cover_name',
                'publisher',
                'book_author',
                'book_genres',
                'book_languages'
            ]
        );

        $results = $this->database::$select
            ->select($selectParams)
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

    public function fetchAllAvailableBooksByPage(int $page, int $limit) : array
    {
        return $this->fetchAvailableByPageWhere(array(), $page, $limit);
    }
}
