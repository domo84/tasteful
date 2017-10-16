<?php

namespace Sunnyexample\Database;

use Sunnyvale\REST\Exceptions\NotFound;
use Sunnyvale\REST\Exceptions\MissingParameter;

abstract class SQLite extends \SQLite3
{
    public function __construct()
    {
        $this->open(__DIR__ . "/../../storage/services.db");

        $this->query("CREATE TABLE IF NOT EXISTS example (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, content TEXT)");
        $this->query("CREATE TABLE IF NOT EXISTS example_item (id INTEGER PRIMARY KEY AUTOINCREMENT, example_id INTEGER, name TEXT)");
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

            throw new MissingParameter("Required key: $key");
        }
    }
}
