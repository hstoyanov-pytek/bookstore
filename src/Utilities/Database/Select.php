<?php

namespace App\Utilities\Database;
use PDO;

class Select extends BaseQuery
{
    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    /// $columns = single dimension array/ string of columns desired
    /// string => "col1, col2, col3...";
    /// array => ["col1", "col2", "col3"...];
    public function select(array|string $columns, bool $distinct = false)
    {
        $this->queryString .= "SELECT ";

        if($distinct === true) $this->queryString .= " DISTINCT ";

        if(empty($columns))
        {
            $this->queryString .= "*";
            return $this;
        }

        if(is_string($columns))
        {
            $this->queryString .= $columns;
            return $this;
        }

        if(!is_array($columns))
        {
            throw new Exception("Columns is not string or array when calling SELECT");
        }

        foreach ($columns as $i => $value)
        {
            $this->queryString .= $i !== array_key_last($columns) ? "{$columns[$i]}, " : "{$columns[$i]}";
        }

        return $this;
    }


    ///
    /// If desired to have different asc/desc order make sure to match the amount of elements
    /// in $ASC_DESC_ORDER array to be equal to the cols array.
    /// Leaving an empty element in $ASC_DESC_ORDER array will be treated as a default value (ASC).
    /// If there is only one element in ASC_DESC_ORDER it will be applied at the end of the query and to all columns
    ///
    /// ASC_DESC_ORDER default value is empty array. Do not pass an array if you're handling the columns through string.
    /// But add the desired ASC_DESC order in the string!
    ///
    public function orderBy(string|array $cols, array $ASC_DESC_ORDER = array())
    {
        $this->queryString .= " ORDER BY ";

        if(is_string($cols))
        {
            $this->queryString .= $cols;
            return $this;
        }

        $countOrder = count($ASC_DESC_ORDER);

        foreach($cols as $index => $col)
        {
            $this->queryString .= ($index !== array_key_last($cols)) ? "{$col} ". ((empty($ASC_DESC_ORDER[$index]) || !array_key_exists($index, $ASC_DESC_ORDER)) ? "ASC" : $ASC_DESC_ORDER[$index]) . ", "
                : "{$col} ". ((empty($ASC_DESC_ORDER[$index]) || !array_key_exists($index, $ASC_DESC_ORDER)) ? "ASC" : $ASC_DESC_ORDER[$index]);
        }

        return $this;
    }
    ///
    /// $conditions = assoc_array/string of conditions that need to be met for the query to go through
    /// string => "table1.col = table2.col";
    /// array[] =>
    ///     ['col' => 'table1.col',
    //    'value' => 'table2.col',
    //    'operators' => '=']
    /// ;
    public function innerJoin(string $column, array|string $condition)
    {
        $this->queryString .= " INNER JOIN ";

        if(empty($column))
        {
            throw new \Exception("Column parameter is empty, when calling INNER_JOIN");
        }
        $this->queryString .= " {$column} ";

        if(empty($condition))
        {
            throw new \Exception("ON Condition is empty when calling INNER_JOIN");
        }
        /// TODO BINDINGS!!!!
        $this->queryString .= ' ON ';

        if(is_string($condition))
        {
            $this->queryString .= $condition;
            return $this;
        }

        if(!is_array($condition))
        {
            throw new \Exception("Condition is not string or array when calling INNER_JOIN!");
        }

        foreach ($condition as $cond)
        {
            $this->queryString .= "{$cond['col']} {$cond['operator']} {$cond['value']} ";
        }

        return $this;
    }

    public function groupBy(array|string $columns)
    {
        $this->queryString .= " GROUP BY ";

        if(is_string($columns))
        {
            $this->queryString .= $columns;
            return $this;
        }

        if(!is_array($columns))
        {
            throw new \Exception("Columns parameter is not string or array when calling GROUP_BY");
        }

        foreach($columns as $i => $value)
        {
            $this->queryString .= ($i !== array_key_last($columns)) ? "{$value}, " : "{$value}";
        }

        return $this;
    }
}