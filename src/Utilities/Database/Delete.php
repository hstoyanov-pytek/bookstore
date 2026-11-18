<?php

namespace App\Utilities\Database;
use PDO;

class Delete extends BaseQuery
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    public function delete()
    {
        $this->queryString = "DELETE ";
        return $this;
    }

}