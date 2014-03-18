<?php

namespace Spol\FSM;

use FileSystemInterface;
use FolderInterface;
use FileInterface;
use DateTime;
use Exception;
use RuntimeException;

class FileSystem implements FileSystemInterface
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @param FileInterface   $file
     * @param FolderInterface $parent
     *
     * @return FileInterface
     */
    public function createFile(FileInterface $file, FolderInterface $parent)
    {
        $now = new DateTime();
        $file->setCreatedTime($now);
        $file->setModifiedTime($now);

        $file->setParentFolder($parent);

        if ($this->db->childExists($parent->getId(), $file->getName())) {
            throw new AlreadyExistsException("A file or folder already exists at that path");
        }

        $id = $this->db->insertFile(
            $file->getName(),
            $file->getSize(),
            $file->getParentFolder()->getId(),
            $file->getCreatedTime(),
            $file->getModifiedTime()
        );

        if (is_int($id)) {
            return $file->setId($id);
        } else {
            throw new RuntimeException("An unknown error occured.");
        }
    }

    /**
     * @param FileInterface $file
     *
     * @return FileInterface
     */
    public function updateFile(FileInterface $file)
    {
        $file->setModifiedTime(new DateTime());

        $this->db->updateFile($file->getId(), $file->getName(), $file->getSize(), $file->getModifiedTime());

        return $file;
    }

    /**
     * @param FileInterface $file
     * @param               $newName
     *
     * @return FileInterface
     */
    public function renameFile(FileInterface $file, $newName)
    {
        $file->setName($newName);

        return $this->updateFile($file);
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    public function deleteFile(FileInterface $file)
    {
        if ($file->getId() === null) {
            return false;
        }

        $this->db->deleteItem($file->getId());
        $file->setId(null);
        return true;
    }

    /**
     * @param FolderInterface $folder
     *
     * @return FolderInterface
     */
    public function createRootFolder(FolderInterface $folder)
    {

        $folderdata = $this->db->getRootFolder();

        if ($folderdata === null) {
            $folder->setCreatedTime(new DateTime());
            $id = $this->db->insertRootFolder($folder->getCreatedTime());
            $folder->setId($id);
        } else {
            $folder->setId($folderdata['id']);
            $folder->setCreatedTime(DateTime::createFromFormat('Y-m-d H:i:s', $folderdata['created_time']));
        }

        return $folder;
    }

    /**
     * @param FolderInterface $folder
     * @param FolderInterface $parent
     *
     * @return FolderInterface
     */
    public function createFolder(FolderInterface $folder, FolderInterface $parent)
    {
        $folder->setCreatedTime(new DateTime());

        if ($this->db->childExists($parent->getId(), $folder->getName())) {
            throw new AlreadyExistsException("A file or folder already exists at that path");
        }

        $folder->setPath($parent->getPath() . $folder->getName() . '/');

        $this->db->insertFolder($folder->getName(), $folder->getPath(), $parent->getId(), $folder->getCreatedTime());
    }

    /**
     * @param FolderInterface $folder
     *
     * @return bool
     */
    public function deleteFolder(FolderInterface $folder)
    {
        foreach ($this->getFiles($folder) as $file) {
            $this->deleteFile($file);
        }

        foreach ($this->getFolders($folder) as $folder) {
            $this->deleteFolder($folder);
        }

        $this->db->deleteItem($folder->getId());
        $folder->setId(null);
        return true;
    }

    /**
     * @param FolderInterface $folder
     * @param                 $newName
     *
     * @return FolderInterface
     */
    public function renameFolder(FolderInterface $folder, $newName)
    {
        $folder->setName($newName);

        $this->db->updateFolder($folder->getId(), $folder->getName(), $folder->getPath());

        return $folder;
    }

    /**
     * @param FolderInterface $folder
     *
     * @return int
     */
    public function getFolderCount(FolderInterface $folder)
    {
        return count($this->getFolders($folder));
    }

    /**
     * @param FolderInterface $folder
     *
     * @return int
     */
    public function getFileCount(FolderInterface $folder)
    {
        return count($this->getFiles($folder));
    }

    /**
     * @param FolderInterface $folder
     *
     * @return int
     */
    public function getDirectorySize(FolderInterface $folder)
    {
        $filesSize = array_reduce($this->getFiles($folder), function ($total, $file) {
            return $total + $file->getSize();
        }, 0);

        $dirsSize = array_reduce($this->getFolders($folder), function ($total, $folder) {
            return $total + $this->getDirectorySize($folder);
        }, 0);

        return $filesSize + $dirsSize;
    }

    /**
     * @param FolderInterface $folder
     *
     * @return FolderInterface[]
     */
    public function getFolders(FolderInterface $folder)
    {
        $foldersdata = $this->db->getChildren($folder->getId(), 'D');
        $parent = $folder;

        $folders = array();
        foreach ($foldersdata as $folderdata) {
            $folders[] = $folder = new Folder($folderdata['name'], (int)$folderdata['size']);
            $folder->setId($folderdata['id']);
            $folder->setCreatedTime(DateTime::createFromFormat('Y-m-d H:i:s', $folderdata['created_time']));
            $folder->setPath($folderdata['path']);
        }

        return $folders;
    }

    /**
     * @param FolderInterface $folder
     *
     * @return FileInterface[]
     */
    public function getFiles(FolderInterface $folder)
    {
        $filesdata = $this->db->getChildren($folder->getId(), 'F');

        $files = array();
        foreach ($filesdata as $filedata) {
            $files[] = $file = new File($filedata['name'], (int)$filedata['size']);
            $file->setId($filedata['id']);
            $file->setCreatedTime(DateTime::createFromFormat('Y-m-d H:i:s', $filedata['created_time']));
            $file->setModifiedTime(DateTime::createFromFormat('Y-m-d H:i:s', $filedata['modified_time']));
            $file->setParentFolder($folder);
        }

        return $files;
    }
}
