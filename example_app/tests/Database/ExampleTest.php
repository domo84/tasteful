<?php

namespace Sunnyexample\TEST\Database;

use PHPUnit\Framework\TestCase;
use Sunnyexample\Database\Example as DB;

use Sunnyexample\TEST\Traits\Provider;

final class ExampleTest extends TestCase
{
    use Provider;

    private $db = null;

    public function setUp()
    {
        $this->db = new DB;
    }

    /**
     * @dataProvider examples
     */
    public function testInsert($example)
    {
        $id = $this->db->insert($example);
        $this->assertTrue(is_int($id));
        $this->assertEquals($example["id"], $id);
    }

    /**
     * @dataProvider examples
     * @depends testInsert
     */
    public function testOne($example)
    {
        $result = $this->db->one($example["id"]);
        $this->assertTrue(is_array($result));
        $this->assertEquals($example, $result);
    }

    /**
     * @depends testInsert
     */
    public function testAll()
    {
        $result = $this->db->all();
        $this->assertTrue(is_array($result));
        $this->assertEquals(2, count($result));
    }

    /**
     * @dataProvider examples
     * @expectedException \Sunnyvale\REST\Exceptions\NotFound
     */
    public function testDelete($example)
    {
        $id = $this->db->insert($example);
        $this->db->delete($id);
        $this->db->one($id);
    }

    /**
     * @dataProvider moreExamples
     * @depends testInsert
     */
    public function testUpdate($example)
    {
        $id = $example["id"];
        $this->db->update($id, $example);
        $result = $this->db->one($id);
        $this->assertEquals($example, $result);
    }

    /**
     * @dataProvider faultyExamples
     * @expectedException \Sunnyvale\REST\Exceptions\MissingParameter
     */
    public function testInsertFaultyData($example)
    {
        $this->db->insert($example);
    }

    public static function tearDownAfterClass()
    {
        $path = __DIR__ . "/../../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
