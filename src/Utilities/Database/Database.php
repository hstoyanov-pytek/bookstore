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

    ///
    /// IMPORTANT, When passing alias, make sure to have aliases equal to columns else there will be an argument mismatch
    /// and the function will throw exception
    public function createParams(array $cols, array $alias = array(), array $values, array $operators) : array
    {
        $params = array();
        if(max(count($cols), count($values), count($alias), count($operators)) !== count($cols))
            throw new \Exception("Mismatch in number of arguments passed in createParams!");

        ///
        // $params = array(
        //  ['col' => 'id',
        //  'alias' => 'columnAlias'
        //  'value' => '1',
        //  'operator' => '=']
        // );
        ///
        for($i = 0; $i < count($cols); $i++)
        {
            if(empty($alias))
            {
                $params[] = [
                    'col' => $cols[$i],
                    'value' => $values[$i],
                    'operator' => $operators[$i]
                ];
            }
            else
            {
                $params[] = [
                    'col' => $cols[$i],
                    'alias' => $alias[$i],
                    'value' => $values[$i],
                    'operator' => $operators[$i]
                ];
            }
        }

        return $params;
    }

}