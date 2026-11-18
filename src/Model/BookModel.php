<?php

namespace App\Model;

class BookModel
{
    public int $id;
    public string $title;
    public ?int $publisher_id;
    public ?string $year_published;
    public ?string $cover_path; // ? -> to allow a null field
    public bool $deleted;
}