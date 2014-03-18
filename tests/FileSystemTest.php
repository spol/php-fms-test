<?php

namespace Spol\FSM\Tests;

use PHPUnit_Framework_TestCase;
use Spol\FSM\File;
use Spol\FSM\Folder;
use Spol\FSM\RootFolder;
use Spol\FSM\FileSystem;
use Spol\FSM\Database;
use Spol\FSM\Config;
use InvalidArgumentException;
use DateTime;

class FilesystemTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
                   ->disableOriginalConstructor()
                   ->getMock();
        $filesystem = new Filesystem($db);
        $this->assertInstanceOf('Spol\FSM\FileSystem', $filesystem);
    }

    public function testCreateRootFolder()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
                   ->disableOriginalConstructor()
                   ->getMock();

        $db->expects($this->once())
           ->method('insertRootFolder')
           ->will($this->returnValue(1));
        $filesystem = new Filesystem($db);
        $root = new RootFolder();
        $filesystem->createRootFolder($root);

        $this->assertEquals(1, $root->getId());
        $this->assertEquals('/', $root->getPath());
    }

    public function testCreateFolder()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
                   ->disableOriginalConstructor()
                   ->getMock();

        $db->expects($this->once())
            ->method('insertRootFolder')
            ->with($this->callback(function ($arg) {
                return $arg instanceof DateTime;
            }));
        $db->expects($this->exactly(2))
            ->method('insertFolder')
            ->with(
                $this->stringContains('Folder'),
                $this->stringStartsWith('/'),
                $this->anything(),
                $this->callback(function ($arg) {
                    return $arg instanceof DateTime;
                })
            );
        $filesystem = new Filesystem($db);
        $root = new RootFolder();
        $filesystem->createRootFolder($root);

        $folder = new Folder('New Folder');

        $filesystem->createFolder($folder, $root);

        $subfolder = new Folder('Sub Folder');

        $filesystem->createFolder($subfolder, $folder);

        $this->assertEquals('/New Folder/Sub Folder/', $subfolder->getPath());
    }

    public function testCreateFile()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
           ->disableOriginalConstructor()
           ->getMock();

        $db->expects($this->once())
            ->method('insertFile')
            ->will($this->returnValue(1));

        $filesystem = new Filesystem($db);
        $root = new RootFolder();
        $filesystem->createRootFolder($root);

        $file = new File('Filename', 1024);
        $filesystem->createFile($file, $root);

        $this->assertEquals(1, $file->getId());
        $this->assertEquals('/Filename', $file->getPath());

    }

    public function testUpdateFile()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
           ->disableOriginalConstructor()
           ->getMock();

        $db->expects($this->once())
            ->method('insertFile')
            ->will($this->returnValue(1));

        $db->expects($this->once())
            ->method('updateFile');

        $filesystem = new Filesystem($db);
        $root = new RootFolder();
        $filesystem->createRootFolder($root);

        $file = new File('Filename', 1024);
        $filesystem->createFile($file, $root);

        $date = $file->getModifiedTime();

        $filesystem->updateFile($file);

        $this->assertFalse($date === $file->getModifiedTime());
    }

    public function testRenameFile()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
           ->disableOriginalConstructor()
           ->getMock();

        $db->expects($this->once())
            ->method('insertFile')
            ->will($this->returnValue(1));

        $filesystem = new Filesystem($db);
        $root = new RootFolder();
        $filesystem->createRootFolder($root);

        $file = new File('Filename', 1024);
        $filesystem->createFile($file, $root);

        $res = $filesystem->renameFile($file, "New Name");

        $this->assertEquals($res, $file);
        $this->assertEquals("New Name", $file->getName());
    }

    public function testDeleteFile()
    {
        $db = $this->getMockBuilder("Spol\FSM\Database")
           ->disableOriginalConstructor()
           ->getMock();

        $db->expects($this->once())
            ->method('insertFile')
            ->will($this->returnValue(1));

        $filesystem = new Filesystem($db);
        $root = new RootFolder();
        $filesystem->createRootFolder($root);

        $file = new File('Filename', 1024);
        $filesystem->createFile($file, $root);

        $this->assertFalse($file->getId() === null);

        $res = $filesystem->deleteFile($file);

        $this->assertTrue($res);
        $this->assertNull($file->getId());

        $res = $filesystem->deleteFile($file);
        $this->assertFalse($res);

    }

    public function testGetFiles()
    {

    }
}
