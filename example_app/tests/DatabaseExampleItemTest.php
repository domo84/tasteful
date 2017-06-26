<?php

require_once "core/exceptions.php";
require_once "database.php";
require_once "lib/traits.php";

use PHPUnit\Framework\TestCase;

/**
 * @covers Example_Item
 */
final class Database_Example_Item_Test extends TestCase
{
    use provider;

    private $db = null;

    public function setUp()
    {
        $this->db = new Database\Example_Item();
    }

    /**
     * @dataProvider items
     */
    public function testInsert($item)
    {
        $id = $this->db->insert($item);
        $this->assertTrue(is_int($id));
        $this->assertEquals($item["_id"], $id);
    }

    /**
     * @dataProvider items
     * @depends testInsert
     */
    public function testOne($item)
    {
        $result = $this->db->one($item["_id"]);
        $this->assertTrue(is_array($result));
        $this->assertEquals($item, $result);
    }

    /**
     * @depends testInsert
     */
    public function testAll()
    {
        $result = $this->db->allByParentId(1);
        $this->assertTrue(is_array($result));
        $this->assertEquals(2, count($result));
    }

    /**
     * @dataProvider items
     * @expectedException Exception\Resource\NotFound
     */
    public function testDelete($item)
    {
        $id = $this->db->insert($item);
        $this->db->delete($id);
        $this->db->one($id);
    }

    /**
     * @dataProvider moreItems
     * @depends testInsert
     */
    public function testUpdate($item)
    {
        $id = $item["_id"];
        $this->db->update($id, $item);
        $result = $this->db->one($id);
        $this->assertEquals($item, $result);
    }

    /**
     * @dataProvider faultyItems
     * @expectedException Exception\Database\MissingKey
     */
    public function testInsertFaultyData($item)
    {
        $this->db->insert($item);
    }

    public static function tearDownAfterClass()
    {
        unlink("data/services.db");
    }
}
