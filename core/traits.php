<?php

trait DB
{
    public function db($name)
    {
        $c = "\\Database\\" . $name;
        return new $c;
    }
}
