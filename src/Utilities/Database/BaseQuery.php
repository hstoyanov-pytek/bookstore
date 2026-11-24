<?php

namespace App\Utilities\Database;
use PDO;
use App\Utilities\WhereOp;

abstract class BaseQuery
{
    public function __construct(
        private PDO $pdo
    ) {}

    public string $queryString = "";
    protected array $binds = array();
    ///
    /// $conditions = assoc_array/string of conditions that need to be met for the query to go through
    /// string => "A > B OR C = D AND id BETWEEN 3 AND 30"; ...
    /// array[] =>
    ///     ['col' => 'id',
    //    'value' => '1',
    //    'operators' => '=']
    /// ;
    /// OR
    /// array[] =>
    ///     ['col' => 'id',
    ///     'alias' => 'col_alias'
    //    'value' => '1',
    //    'operators' => '=']
    ///
    /// $operator = enum of operators for the where clause, the options are limited by the enum
    /// Only one operator can be applied for a query, if NOT is selected, then it will be applied at the beginning of the query
    /// and the operator will be ignored and, AND will be applied instead
    ///
    public function where(array $condition, WhereOp $operator = WhereOp::AND) : object
    {
        $this->queryString .= " WHERE ";

        if($operator === WhereOp::NOT) $this->queryString .= $operator->value;

        if(empty($condition))
        {
            $this->queryString .= "1";
            return $this;
        }

        foreach ($condition as $i => $value)
        {
            if(empty($value['alias']))
            {
                $this->queryString .= ($i !== array_key_last($condition)) ?
                    "{$value['col']} {$value['operator']} :{$value['col']} " . ($operator === WhereOp::NOT ? WhereOp::AND->value : $operator->value)
                : "{$value['col']} {$value['operator']} :{$value['col']} ";

                $this->createBinds($value['col'], $value['value']);
            }
            else
            {
                $this->queryString .= ($i !== array_key_last($condition)) ?
                    "{$value['col']} {$value['operator']} :{$value['alias']} " . ($operator === WhereOp::NOT ? WhereOp::AND->value : $operator->value)
                    : "{$value['col']} {$value['operator']} :{$value['alias']} ";

                $this->createBinds($value['alias'], $value['value']);
            }
        }

        return $this;
    }

    public function getResults(string $modelClass = "") : array
    {
        $stmt = $this->pdo->prepare($this->queryString);

        if(empty($this->binds))
        {
            $stmt->execute();
            if(empty($modelClass))
            {
                $this->clearProperties();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            $this->clearProperties();
            return $stmt->fetchAll(PDO::FETCH_CLASS, $modelClass);
        }

        foreach ($this->binds as $bind)
        {
            $stmt->bindValue($bind['key'], $bind['value'], $bind['type']);
        }
        $stmt->execute();

        if(empty($modelClass))
        {
            $this->clearProperties();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $this->clearProperties();
        return $stmt->fetchAll(PDO::FETCH_CLASS, $modelClass);
    }

    public function getResult(string $modelClass = "") : null|object
    {
        $stmt = $this->pdo->prepare($this->queryString);

        if(empty($this->binds))
        {
            $stmt->execute();
            if (empty($modelClass))
            {
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
            }
            else
            {
                $stmt->setFetchMode(PDO::FETCH_CLASS, $modelClass);
            }

            $this->clearProperties();
            return $stmt->fetch();
        }

        foreach ($this->binds as $bind)
        {
            $stmt->bindValue($bind['key'], $bind['value'], $bind['type']);
        }

        $stmt->execute();

        if (empty($modelClass))
        {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }
        else
        {
            $stmt->setFetchMode(PDO::FETCH_CLASS, $modelClass);
        }

        $result = $stmt->fetch();
        if(!empty($result))
        {
            $this->clearProperties();
            return $result;
        }

        $this->clearProperties();
        return null;
    }

    public function exec() : void
    {
        $stmt = $this->pdo->prepare($this->queryString);

        foreach ($this->binds as $bind)
        {
            $stmt->bindValue($bind['key'], $bind['value'], $bind['type']);
        }
        $stmt->execute();

        $this->clearProperties();
    }

    public function from(string $table) : object
    {
        if(empty($table))
        {
            throw new \Exception("Missing FROM table");
        }
        $this->queryString .= " FROM " .$table;

        return $this;
    }

    protected function createBinds(string $column, string $value) : void
    {
        $type = PDO::PARAM_STR;
        if(is_numeric($value))
        {
            $value += 0; // convert the string to int/float
            if(is_int($value)) $type = PDO::PARAM_INT; // test if the value is integer, and leave float as a string
        }

        $this->binds[] = [
            'key' => ":{$column}",
            'value' => "{$value}",
            'type' => $type
        ];
    }

    public function getDebugQuery() : string
    {
        return $this->queryString;
    }

    private function clearProperties() : void
    {
        $this->queryString = "";
        $this->binds = array();
    }
}