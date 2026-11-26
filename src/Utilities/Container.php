<?php

namespace App\Utilities;

class Container
{
    private array $instances = array();
    private array $bindings = array();

    public function bind(string $what, \Closure $binding) : void
    {
        $this->bindings[$what] = $binding;
    }

    public function get(string $what)
    {
        if(empty($this->instances[$what]))
        {
            if(empty($this->bindings[$what]))
            {
                echo "COULD NOT BUILD {$what}";
                die();
            }
            $this->instances[$what] = $this->bindings[$what]();
        }
        return $this->instances[$what];
    }
}