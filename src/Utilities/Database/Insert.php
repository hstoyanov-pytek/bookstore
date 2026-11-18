<?php

namespace App\Utilities\Database;
use PDO;

class Insert extends BaseQuery
{
    private int $columnArgs = 0;
    private int $valueArgs = 0;

    public function __construct(PDO $pdo) {
        parent::__construct($pdo);
    }

    public function insertInto(string $table, array|string $columns, )
    {
        $this->queryString .= "INSERT INTO ";

        if(empty($table))
        {
            throw new \Exception("No table selected when calling INSERT_INTO");
        }

        $this->queryString .= $table;

        if(is_string($columns))
        {
            $this->columnArgs = count(explode(",", $columns));
            $this->queryString .= "({$columns})";
            return $this;
        }

        $this->queryString .= " (";

        if(is_array($columns))
        {
            $this->columnArgs = count($columns);

            foreach ($columns as $i => $value)
            {

                $this->queryString .= ($i !== array_key_last($columns)) ? "{$value}, " : "{$value}";

                $this->createBinds($value, "");
            }
        }


        $this->queryString .= ") ";
        return $this;
    }

    ///
    /// $values => array(array())
    /// When passing values to for the insert query, make sure you wrap them in an array()!
    ///
    public function values(string|array $values)
    {
        $this->queryString .= " VALUES ";

        if(empty($values))
        {
            throw new \Exception("Values array is empty when calling INSERT::VALUES");
        }
        $this->queryString .= " (";

        for ($i=0; $i < count($this->binds); $i++)
        {
            $this->queryString .= $i !== array_key_last($this->binds) ? "{$this->binds[$i]['key']}, " : $this->binds[$i]['key'];
        }
        $this->queryString .= ") ";


        if(is_string($values))
        {
            //// TODO: SOLVE THE PROBLEM IF THE STRING IS PASSING MULTIPLE ENTRIES!
            //// the case INSERT INTO Customers (CustomerName, ContactName, Address, City, PostalCode, Country)
            //VALUES
            //('Cardinal', 'Tom B. Erichsen', 'Skagen 21', 'Stavanger', '4006', 'Norway'),
            //('Greasy Burger', 'Per Olsen', 'Gateveien 15', 'Sandnes', '4306', 'Norway'),
            //('Tasty Tee', 'Finn Egan', 'Streetroad 19B', 'Liverpool', 'L1 0AA', 'UK');

            $this->valueArgs = count(explode(",", $values));
            if($this->columnArgs !== $this->valueArgs)
            {
                throw new \Exception("Number of columns and values mismatch when calling INSERT::VALUES");
            }

            $this->queryString .= "({$values})";
            return $this;
        }

        if(is_array($values))
        {
            $this->valueArgs = count($values);
            for ($i = 0; $i < $this->valueArgs; $i++)
            {
                if($this->columnArgs != $this->valueArgs)
                {
                    throw new \Exception("Number values does not match the passed amount of columns when calling INSERT::VALUES");
                }
                $this->binds[$i]['value'] = $values[$i];
            }
        }
        return $this;
    }
}