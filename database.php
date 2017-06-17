<?php

namespace Database;

abstract class Database extends \SQLite3
{
    public function __construct()
    {
        $this->open(__DIR__ . "/data/services.db");

        $this->query("CREATE TABLE IF NOT EXISTS example (_id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, content TEXT)");
        $this->query("CREATE TABLE IF NOT EXISTS example_item (_id INTEGER PRIMARY KEY AUTOINCREMENT, example_id INTEGER, name TEXT)");
    }

    public static function associate($result)
    {
        $data = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($data, $row);
        }
        return $data;
    }

    public static function associateOne($result)
    {
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return $row;
        }

        throw new \Exception\Resource\NotFound();
    }

    public function assert($expected, $actual)
    {
        foreach ($expected as $key) {
            if (array_key_exists($key, $actual)) {
                continue;
            }

            throw new \Exception\Database\MissingKey("Missing required key: $key");
        }
    }
}

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
