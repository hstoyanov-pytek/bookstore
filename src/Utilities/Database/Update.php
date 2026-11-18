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
    ///     'alias' => 'col_alias'
    ///     'value => 'value'
    /// ]
    ///
    public function set(array $columns)
    {
        $this->queryString .= " SET ";

        $aliasExist = array_key_exists("alias", $columns);

        foreach ($columns as $i => $val)
        {
            if(!$aliasExist)
            {
                $this->queryString .= ($i !== array_key_last($columns)) ?
                    "{$val['col']} = :{$val['col']}, "
                    : "{$val['col']} = :{$val['col']}";

                $this->createBinds($val['col'], $val['value']);
            }
            else
            {
                $this->queryString .= ($i !== array_key_last($columns)) ?
                    "{$val['col']} = :{$val['alias']}, "
                    : "{$val['col']} = :{$val['alias']} ";

                $this->createBinds($val['alias'], $val['value']);
            }
        }

        return $this;
    }

    public function ignore()
    {

    }
}