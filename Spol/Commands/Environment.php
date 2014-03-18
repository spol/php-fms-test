<?php

namespace Spol\FSM\Commands;

use FolderInterface;

class Environment
{
    protected $currentFolder;
    protected $folderStack;

    public function __construct()
    {
        $this->folderStack = array();
    }

    public function setCurrentFolder(FolderInterface $folder)
    {
        $this->currentFolder = $folder;
    }

    public function getCurrentFolder()
    {
        return $this->currentFolder;
    }

    public function pushFolderStack(FolderInterface $folder)
    {
        array_push($this->folderStack, $folder);
    }

    public function popFolderStack()
    {
        $this->setCurrentFolder(array_pop($this->folderStack));
    }

    public function getFolderStackSize()
    {
        return count($this->folderStack);
    }
}
