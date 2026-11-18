<?php

namespace App\Model;

class BookDetailsDTO
{
    public int $book_id;
    public string $book_title;
    public string $year_published;
    public string $publisher;
    public string $book_author;
    public string $book_genres;
    public string $book_languages;
    public bool $book_available;

    public function arrayToObject(array $values) : BookDetailsDTO
    {
        $this->book_title = @(string)$values['title'];
        $this->year_published = @(string)$values['year_published'];
        $this->publisher = @(string)$values['publisher'];
        $this->book_author = @(string)$values['author'];
        $this->book_genres = @(string)$values['genre'];
        $this->book_languages = @(string)$values['language'];
        $this->book_available = @(bool)$values['available'];

        return $this;
    }
}