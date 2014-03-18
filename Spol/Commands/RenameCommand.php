<?php

namespace Spol\FSM\Commands;

use Spol\FSM\File;

class RenameCommand extends Command
{
    public function main($argv, $argc)
    {
        if ($argc < 3) {
            return "No name specified for new directory.";
        }

        $sourceItem = $this->findChild($this->env->getCurrentFolder(), $argv[1]);

        $targetItem = $this->findChild($this->env->getCurrentFolder(), $argv[2]);

        if ($sourceItem === null) {
            return "No such file or directory.";
        }

        if ($targetItem !== null) {
            return "A file or directory already exists with the new name.\n";
        }

        if ($sourceItem instanceof File) {
            $this->filesystem->renameFile($sourceItem, $argv[2]);
        } else {
            $this->filesystem->renameFolder($sourceItem, $argv[2]);
        }

        return "";
    }

    public function getName()
    {
        return 'rename';
    }
}
