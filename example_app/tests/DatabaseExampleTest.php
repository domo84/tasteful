<?php

namespace Sunnyexample\TEST;

use PHPUnit\Framework\TestCase;
use Sunnyexample\Database;

final class DatabaseExampleTest extends TestCase
{
    use provider;

    private $db = null;

    public function setUp()
    {
        $this->db = new Database\Example();
    }

    /**
     * @dataProvider examples
     */
    public function testInsert($example)
    {
        $id = $this->db->insert($example);
        $this->assertTrue(is_int($id));
        $this->assertEquals($example["_id"], $id);
    }

    /**
     * @dataProvider examples
     * @depends testInsert
     */
    public function testOne($example)
    {
        $result = $this->db->one($example["_id"]);
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
     * @expectedException \DomainException
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
        $id = $example["_id"];
        $this->db->update($id, $example);
        $result = $this->db->one($id);
        $this->assertEquals($example, $result);
    }

    /**
     * @dataProvider faultyExamples
     * @expectedException \InvalidArgumentException
     */
    public function testInsertFaultyData($example)
    {
        $this->db->insert($example);
    }

    public static function tearDownAfterClass()
    {
        unlink("storage/services.db");
    }
}
