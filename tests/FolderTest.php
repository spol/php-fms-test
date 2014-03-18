<?php

namespace Spol\FSM\Tests;

use PHPUnit_Framework_TestCase;
use Spol\FSM\Folder;
use InvalidArgumentException;
use Datetime;

class FolderTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $folder = new Folder("Name");
        $this->assertInstanceOf("Spol\FSM\Folder", $folder);
        $this->assertEquals("Name", $folder->getName());
    }

    public function testName()
    {
        $folder = new Folder("Name");
        $this->assertEquals("Name", $folder->getName());
        $res = $folder->setName("NewName");
        $this->assertEquals("NewName", $folder->getName());
        $this->assertEquals($res, $folder);

        try {
            $folder->setName('Foo/Bar');
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Invalid name provided.', $exp->getMessage());
        }
    }

    public function testCreatedTime()
    {
        $folder = new Folder("Name");
        $this->assertNull($folder->getCreatedTime());

        $time = new DateTime("2014-01-01");

        $res = $folder->setCreatedTime($time);

        $this->assertEquals(1388534400, $folder->getCreatedTime()->getTimestamp());
        $this->assertEquals($res, $folder);

        try {
            $folder->setCreatedTime(1388534400);
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Argument not an instance of DateTime class.', $exp->getMessage());
        }
    }

    public function testPath()
    {
        $folder = new Folder("Name");
        $this->assertNull($folder->getPath());

        $res = $folder->setPath('/foo/bar/');
        $this->assertEquals('/foo/bar/', $folder->getPath());
        $this->assertEquals($res, $folder);

        try {
            $folder->setPath("Foo:Bar");
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Invalid path specified.', $exp->getMessage());
        }
    }
}
