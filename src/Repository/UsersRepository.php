<?php

namespace App\Repository;
use \App\Model\UserDetailsDTO;
use \App\Model\RolesModel;

class UsersRepository
{
    public function __construct(
        private \App\Utilities\Database\Database $database
    ){}

    public function fetchAllUsers() : array
    {
        $results = $this->database::$select
            ->select("
                users.id as user_id,
                users.email as user_email,
                users.name as user_name,
                roles.name as user_role
            ")
            ->from("users")
            ->innerJoin("users_roles", "users_roles.users_id = users.id")
            ->innerJoin("roles", "roles.id = users_roles.roles_id")
            ->getResults(UserDetailsDTO::class);

        return $results;
    }

    public function getUserById($id) : object
    {
        $whereParam = $this->database->createParams(cols:["users.id"], alias:["userId"], values:[$id], operators:["="]);

        $result = $this->database::$select
            ->select("
                users.id as user_id,
                users.name as user_name,
                roles.name as user_role
            ")
            ->from("users")
            ->innerJoin("users_roles", "users_roles.users_id = users.id")
            ->innerJoin("roles", "roles.id = users_roles.roles_id")
            ->where($whereParam)
            ->getResult(UserDetailsDTO::class);

        return $result;
    }

    public function getRoles() : array
    {
        $results = $this->database::$select
            ->select("")
            ->from("roles")
            ->getResults(RolesModel::class);

        return $results;
    }
///
    /// $columns = assoc_array
    /// [
    ///     'col' => 'colName',
    ///     'alias' => 'col_alias'
    ///     'value => 'value'
    /// ]
    ///
    public function editUser(UserDetailsDTO $details) : void
    {
        if(empty($details)){ return ;}
        $whereParams = $this->database->createParams(cols:["id"], values:[$details->user_id], operators:["="]);

        $detailsArray = $details->objectToArray();

        $setParams = array();

        foreach ($detailsArray as $key => $value)
        {
            if(!empty($value))
            {
                $setParams[] = [
                    'col' => $key,
                    'value' => $value
                ];
            }
        }

        $this->database::$update
            ->update("users")
            ->set($setParams)
            ->where($whereParams)
            ->exec();
    }

    public function deleteUserById(int $id) : void
    {
        $whereUser = $this->database->createParams(cols:["id"], values:[$id], operators:["="]);

        $result = $this->database::$delete
            ->delete()
            ->from("users")
            ->where($whereUser)
            ->exec();
    }
}