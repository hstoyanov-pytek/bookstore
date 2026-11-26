<?php

namespace App\Repository;
use \App\Model\UserDetailsDTO;
use \App\Model\RolesModel;
use \App\Model\CountModel;

use App\Utilities\Database\SelectSpec;

class UsersRepository
{
    public function __construct(
        private \App\Utilities\Database\Database $database
    ){}

    public function getCountUsers() : int
    {
        $result = $this->database::$select
            ->select(spec: SelectSpec::COUNT)
            ->count()
            ->addAs()
            ->from("users")
            ->getResult(CountModel::class);

        return $result->count;
    }

    public function fetchAllUsers() : array
    {
        $selectParams = $this->database->createSelectParams(
        cols:[
            'users.id',
            'users.email',
            'users.name',
            'roles.name'
        ],
        alias: [
            'user_id',
            'user_email',
            'user_name',
            'user_role'
        ]);

        $results = $this->database::$select
            ->select($selectParams)
            ->from("users")
            ->innerJoin("users_roles", "users_roles.users_id = users.id")
            ->innerJoin("roles", "roles.id = users_roles.roles_id")
            ->getResults(UserDetailsDTO::class);

        return $results;
    }

    public function fetchUserById($id) : object
    {
        $whereParam = $this->database->createParams(cols:["users.id"], alias:["userId"], values:[$id], operators:["="]);
        $selectParams = $this->database->createSelectParams(
          cols:[
              'users.id',
              'users.name',
              'roles.name'
            ],
          alias:[
              'user_id',
              'user_name',
              'user_role'
            ]
        );

        $result = $this->database::$select
            ->select($selectParams)
            ->from("users")
            ->innerJoin("users_roles", "users_roles.users_id = users.id")
            ->innerJoin("roles", "roles.id = users_roles.roles_id")
            ->where($whereParam)
            ->getResult(UserDetailsDTO::class);

        return $result;
    }

    public function fetchRoles() : array
    {
        $results = $this->database::$select
            ->select()
            ->from("roles")
            ->getResults(RolesModel::class);

        return $results;
    }

    public function updateUser(UserDetailsDTO $details) : void
    {
        if(empty($details)){
            throw new \Exception("UserDetailsDTO is null");
        }
        $whereParams = $this->database->createParams(cols:["id"], values:[$details->user_id], operators:["="]);

        $this->database::$update
            ->update("users")
            ->set(array(['col' => 'name', 'value' => $details->user_name]))
            ->where($whereParams)
            ->exec();

        $whereRole = $this->database->createParams(cols: ['name'], values: [$details->user_role], operators: ["="]);
        $roleFound = $this->database::$select
            ->select()
            ->from("roles")
            ->where($whereRole)
            ->getResult(RolesModel::class);

        $whereUsersRoles = $this->database->createParams(cols:['users_id'], values: [$details->user_id], operators: ["="]);
        $this->database::$update
            ->update("users_roles")
            ->set(array(['col' => 'roles_id', 'value' => $roleFound->id]))
            ->where($whereUsersRoles)
            ->exec();
    }

    public function deleteUserById(int $id) : void
    {
        $whereUser = $this->database->createParams(cols:["id"], values:[$id], operators:["="]);

        $this->database::$delete
            ->delete()
            ->from("users")
            ->where($whereUser)
            ->exec();
    }
}