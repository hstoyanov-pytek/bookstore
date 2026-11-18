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
}