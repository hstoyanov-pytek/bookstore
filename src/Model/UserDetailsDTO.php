<?php

namespace App\Model;

class UserDetailsDTO
{
    public int $user_id;
    public string $user_email;
    public string $user_password;
    public string $user_name;
    public string $user_role;


    public function arrayToObject(array $details) : object
    {
        $this->user_id = @(int)$details['user_id'];
        $this->user_email = @(string)$details['user_email'];
        $this->user_password = @(string)$details['user_password'];
        $this->user_name = @(string)$details['user_name'];
        $this->user_role = @(string)$details['user_role'];

        return $this;
    }

    public function objectToArray() : array
    {
        return array(
            'user_id' => $this->user_id,
            'user_email' => $this->user_email,
            'user_name' => $this->user_name,
            'user_password' => $this->user_password,
            'user_role' => $this->user_role,
        );
    }
}