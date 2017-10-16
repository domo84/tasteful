<?php

namespace Sunnyexample\TEST\Database;

use PHPUnit\Framework\TestCase;
use Sunnyexample\Database\ExampleItem as DB;

use Sunnyexample\TEST\Traits\Provider;

final class ExampleItemTest extends TestCase
{
    use Provider;

    private $db = null;

    public function setUp()
    {
        $this->db = new DB();
    }

    /**
     * @dataProvider items
     */
    public function testInsert($item)
    {
        $id = $this->db->insert($item);
        $this->assertTrue(is_int($id));
        $this->assertEquals($item["id"], $id);
    }

    /**
     * @dataProvider items
     * @depends testInsert
     */
    public function testOne($item)
    {
        $result = $this->db->one($item["id"]);
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
     * @expectedException \Sunnyvale\REST\Exceptions\NotFound
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
        $id = $item["id"];
        $this->db->update($id, $item);
        $result = $this->db->one($id);
        $this->assertEquals($item, $result);
    }

    /**
     * @dataProvider faultyItems
     * @expectedException \Sunnyvale\REST\Exceptions\MissingParameter
     */
    public function testInsertFaultyData($item)
    {
        $this->db->insert($item);
    }

    public static function tearDownAfterClass()
    {
        $path = __DIR__ . "/../../storage/services.db";
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
