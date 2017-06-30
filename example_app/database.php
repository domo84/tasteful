<?php

namespace Sunnyexample;

use Sunnyvale\REST\Exceptions\NotFound;

abstract class Database extends \SQLite3
{
    public function __construct()
    {
        $this->open(__DIR__ . "/storage/services.db");

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

        throw new NotFound("Resource not found");
    }

    public function assert($expected, $actual)
    {
        foreach ($expected as $key) {
            if (array_key_exists($key, $actual)) {
                continue;
            }

            throw new \InvalidArgumentException("Missing required key: $key");
        }
    }
}
