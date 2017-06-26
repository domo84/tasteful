<?php

namespace Sunnyexample\Database;

use Sunnyexample\Database;

class Example extends Database
{
    public function all()
    {
        $result = $this->query("SELECT * FROM example");
        return Database::associate($result);
    }

    public function delete(string $id)
    {
        $statement = $this->prepare("DELETE FROM example WHERE _id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function insert(array $values): int
    {
        $this->assert(array("name", "content"), $values);
        $statement = $this->prepare("INSERT INTO example VALUES(NULL, :name, :content)");
        $statement->bindValue(":name", $values["name"]);
        $statement->bindValue(":content", $values["content"]);
        $statement->execute();
        return (int) $this->lastInsertRowId();
    }

    public function one(string $id): array
    {
        $statement = $this->prepare("SELECT * FROM example WHERE _id = :id");
        $statement->bindValue(":id", $id);
        $result = $statement->execute();
        return Database::associateOne($result);
    }

    public function update(string $id, array $values)
    {
        $statement = $this->prepare("UPDATE example SET name = :name, content = :content WHERE _id = :id");
        $statement->bindValue(":name", $values["name"]);
        $statement->bindValue(":content", $values["content"]);
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
}
