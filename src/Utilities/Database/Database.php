<?php

namespace App\Utilities\Database;
use PDO;
class Database
{
    public static Select $select;
    public static Insert $insert;
    public static Update $update;
    public static Delete $delete;

    public function __construct(private PDO $pdo){
        self::$select = new Select($pdo);
        self::$insert = new Insert($pdo);
        self::$update = new Update($pdo);
        self::$delete = new Delete($pdo);
    }

    public function createParams(array $cols, array $values, array $operators) : array
    {
        $params = array();
        if(max(count($cols), count($values), count($operators)) !== count($cols))
            throw new \Exception("Mismatch in number of arguments passed in createParams!");

        ///
        // $params = array(
        //  ['col' => 'id',
        //  'value' => '1',
        //  'operator' => '=']
        // );
        ///
        for($i = 0; $i < count($cols); $i++)
        {
            $params[] = [
                'col' => $cols[$i],
                'value' => $values[$i],
                'operator' => $operators[$i]
            ];
        }

        return $params;
    }

}