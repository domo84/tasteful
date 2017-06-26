<?php

namespace Sunnyexample\Database;

use Sunnyexample\Database;

class Example_Item extends Database
{
    public function allByParentId(string $parentId): array
    {
        $statement = $this->prepare("SELECT * FROM example_item WHERE example_id = :example_id");
        $statement->bindValue(":example_id", $parentId);
        $result = $statement->execute();
        return Database::associate($result);
    }

    public function one(string $id): array
    {
        $statement = $this->prepare("SELECT * FROM example_item WHERE _id = :id");
        $statement->bindValue(":id", $id);
        $result = $statement->execute();
        return Database::associateOne($result);
    }

    public function update(string $id, array $values)
    {
        $statement = $this->prepare("UPDATE example_item SET name = :name WHERE _id = :id");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":name", $values["name"]);
        $statement->execute();
    }

    public function insert(array $values): int
    {
        $this->assert(array("name", "example_id"), $values);
        $statement = $this->prepare("INSERT INTO example_item VALUES(NULL, :example_id, :name)");
        $statement->bindValue(":example_id", $values["example_id"]);
        $statement->bindValue(":name", $values["name"]);
        $statement->execute();
        return (int) $this->lastInsertRowId();
    }

    public function delete(string $id)
    {
        $statement = $this->prepare("DELETE FROM example_item WHERE _id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
}
