<?php

namespace Sunnyexample\TEST\Traits;

trait Provider
{
    public function examples()
    {
        return [
            [["id" => 1, "name" => "Name#1", "content" => "Content#1"]],
            [["id" => 2, "name" => "Name#2", "content" => "Content#2"]]
        ];
    }

    public function moreExamples()
    {
        return [
            [["id" => 1, "name" => "New_Name#1", "content" => "New_Content#1"]],
            [["id" => 2, "name" => "New_Name#2", "content" => "New_Content#2"]]
        ];
    }

    public function faultyExamples()
    {
        return [
            [["id" => 601, "name" => "cider"]],
            [["id" => 602, "name" => "cider", "body" => "moo"]],
            [["id" => 603, "content" => "cider"]],
            [["id" => 604, "content" => "cider", "body" => "moo"]]
        ];
    }

    public function items()
    {
        return [
            [["id" => 1, "example_id" => 1, "name" => "Item#1"]],
            [["id" => 2, "example_id" => 1, "name" => "Item#2"]]
        ];
    }

    public function moreItems()
    {
        return [
            [["id" => 1, "example_id" => 1, "name" => "new_name#1"]],
            [["id" => 2, "example_id" => 1, "name" => "il_nome#2"]]
        ];
    }

    public function faultyItems()
    {
        return [
            [["id" => 500, "nome" => "Hest"]],
            [["id" => 501, "gnome" => "kde"]]
        ];
    }
}
