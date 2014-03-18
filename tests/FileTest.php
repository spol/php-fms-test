<?php

namespace Spol\FSM\Tests;

use PHPUnit_Framework_TestCase;
use Spol\FSM\File;
use Spol\FSM\Folder;
use InvalidArgumentException;
use Datetime;

class FileTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $file = new File("Name");
        $this->assertInstanceOf("Spol\FSM\File", $file);
        $this->assertEquals("Name", $file->getName());
        $this->assertEquals(0, $file->getSize());

        $file = new File("Name2", 1);
        $this->assertInstanceOf("Spol\FSM\File", $file);
        $this->assertEquals("Name2", $file->getName());
        $this->assertEquals(1, $file->getSize());

        try {
            $file = new File("Name2", "ZERO");
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Specified size is not an int.', $exp->getMessage());
        }
    }

    public function testName()
    {
        $file = new File("Name");
        $this->assertEquals("Name", $file->getName());
        $res = $file->setName("NewName");
        $this->assertEquals("NewName", $file->getName());
        $this->assertEquals($res, $file);

        try {
            $file->setName("");
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Invalid name provided.', $exp->getMessage());
        }
    }

    public function testSize()
    {
        $file = new File("Name", 10);
        $this->assertEquals(10, $file->getSize());

        $res = $file->setSize(50);
        $this->assertEquals(50, $file->getSize());
        $this->assertEquals($res, $file);

        try {
            $file->setSize("");
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Specified size is not an int.', $exp->getMessage());
        }
    }

    public function testCreatedTime()
    {
        $file = new File("Name", 10);
        $this->assertNull($file->getCreatedTime());

        $time = new DateTime("2014-01-01");

        $res = $file->setCreatedTime($time);

        $this->assertEquals(1388534400, $file->getCreatedTime()->getTimestamp());
        $this->assertEquals($res, $file);

        try {
            $file->setCreatedTime(1388534400);
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Argument not an instance of DateTime class.', $exp->getMessage());
        }
    }

    public function testModifiedTime()
    {
        $file = new File("Name", 10);
        $this->assertNull($file->getModifiedTime());

        $time = new DateTime("2014-01-01");

        $res = $file->setModifiedTime($time);

        $this->assertEquals(1388534400, $file->getModifiedTime()->getTimestamp());
        $this->assertEquals($res, $file);

        try {
            $file->setModifiedTime(1388534400);
            $this->fail("Expected exception not thrown.");
        } catch (InvalidArgumentException $exp) {
            $this->assertEquals('Argument not an instance of DateTime class.', $exp->getMessage());
        }
    }

    public function testParentFolder()
    {
        $file = new File("File");
        $folder = new Folder("Folder");

        $this->assertNull($file->getParentFolder());

        $res = $file->setParentFolder($folder);

        $this->assertEquals($res, $file);
        $this->assertEquals($folder, $file->getParentFolder());
    }

    public function testPath()
    {
        $file = new File("File");
        $folder = new Folder("Folder");
        $folder->setPath('/Parent/Folder/');
        $file->setParentFolder($folder);

        $this->assertEquals('/Parent/Folder/File', $file->getPath());
    }
}
