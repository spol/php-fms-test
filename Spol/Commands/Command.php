<?php

namespace Spol\FSM\Commands;

use FolderInterface;

abstract class Command
{

    protected $filesystem;

    protected $env;

    public function __construct($filesystem, $env)
    {
        $this->filesystem = $filesystem;
        $this->env = $env;
    }

    public function findChild(FolderInterface $folder, $name)
    {
        $subitems = array_merge(
            $this->filesystem->getFolders($folder),
            $this->filesystem->getFiles($folder)
        );

        $subitems = array_values(array_filter($subitems, function ($item) use ($name) {
            return $item->getName() === $name;
        }));

        if (empty($subitems)) {
            return null;
        } else {
            return $subitems[0];
        }
    }

    abstract public function main($argv, $argc);

    abstract public function getName();
}
