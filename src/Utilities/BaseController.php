<?php

namespace App\Utilities;

use \App\Utilities\AuthService;

abstract class BaseController
{
   protected function DisplayLoginNav() : bool
   {
        AuthService::ensureSessionUnique();
        if(!empty($_SESSION['userId']))
        {
            return false;
        }

        return true;
   }
}