<?php

namespace Database;

abstract class Database extends \SQLite3
{
	public function __construct()
	{
		$this->open("../data/services.db");

		$this->query("CREATE TABLE IF NOT EXISTS example ( _id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, content TEXT )");
	}

	public static function associate($result)
	{
		$data = array();
		while($row = $result->fetchArray(SQLITE3_ASSOC))
		{
			array_push($data, $row);
		}
		return $data;
	}

	public static function associateOne($result)
	{
		while($row = $result->fetchArray(SQLITE3_ASSOC))
		{
			return $row;
		}

		throw new \Exception\Resource\NotFound(); // return null;
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

	public function insert(\stdClass $values): string
	{
		$statement = $this->prepare("INSERT INTO example VALUES(NULL, :name, :content)");
		$statement->bindValue(":name", $values->name);
		$statement->bindValue(":content", $values->content);
		$statement->execute();
		return $this->lastInsertRowId();
	}

	public function one(string $id): array
	{
		$statement = $this->prepare("SELECT * FROM example WHERE _id = :id");
		$statement->bindValue(":id", $id);
		$result = $statement->execute();
		return Database::associateOne($result);
	}

	public function update(string $id, \stdClass $values)
	{
		// Loop through $values->items and update one by one?

		$statement = $this->prepare("UPDATE example SET name = :name, content = :content WHERE _id = :id");
		$statement->bindValue(":name", $values->name);
		$statement->bindValue(":content", $values->content);
		$statement->bindValue(":id", $id);
		$statement->execute();
	}
}

class Example_Item extends Database
{
	public function allByParentId($exampleId)
	{
		$statement = $this->prepare("SELECT * FROM example_item WHERE example_id = :example_id ORDER BY position ASC");
		$statement->bindValue(":example_id", $exampleId);
		$result = $statement->execute();
		return Database::associate($result);
	}

	public function one($id)
	{
		$statement = $this->prepare("SELECT * FROM example_item WHERE _id = :id");
		$statement->bindValue(":id", $id);
		$result = $statement->execute();
		return Database::associateOne($result);
	}

	public function update($id, $values)
	{
		$statement = $this->prepare("UPDATE example_item SET name = :name, value = :value, position = :position WHERE _id = :id");
		$statement->bindValue(":id", $id);
		$statement->bindValue(":name", $values->name);
		$statement->bindValue(":value", $values->value);
		$statement->bindValue(":position", $values->position);
		$statement->execute();
	}

	public function insert($exampleId, $values)
	{
		$statement = $this->prepare("INSERT INTO example_item VALUES(NULL, :example_id, :name, :value, 100)");
		$statement->bindValue(":example_id", $exampleId);
		$statement->bindValue(":name", $values->name);
		$statement->bindValue(":value", $values->value);
		$statement->execute();
		return $this->lastInsertRowId();
	}

	public function delete($id)
	{
		$statement = $this->prepare("DELETE FROM example_item WHERE _id = :id");
		$statement->bindValue(":id", $id);
		$statement->execute();
	}
}
