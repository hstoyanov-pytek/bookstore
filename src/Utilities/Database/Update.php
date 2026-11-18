<?php

namespace App\Utilities\Database;
use PDO;

class Update extends BaseQuery
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    public function update(string $table)
    {
        $this->queryString = "UPDATE ";
        if(!is_string($table))
        {
            throw new \Exception("Table parameter invalid when calling UPDATE::Update");
        }

        $this->queryString .= " {$table} ";

        return $this;
    }

    ///
    /// $columns = assoc_array
    /// [
    ///     'col' => 'colName',
    ///     'value => 'value'
    /// ]
    ///
    public function set(array $columns)
    {
        $this->queryString .= " SET ";

        foreach ($columns as $column => $val)
        {
            $this->queryString .= ($column !== array_key_last($columns)) ? "{$column} = :{$column}, "
                : "{$column} = :{$column}";

            $this->createBinds($column, $val);
        }

        return $this;
    }

    public function ignore()
    {

    }
}