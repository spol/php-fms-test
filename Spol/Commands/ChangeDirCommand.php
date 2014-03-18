<?php

namespace Spol\FSM\Commands;

use Spol\FSM\Folder;

class ChangeDirCommand extends Command
{
    public function main($argv, $argc)
    {
        if ($argc < 2) {
            return "No name specified for new directory.";
        }

        $target = $argv[1];
        if ($target === '..') {
            if ($this->env->getFolderStackSize() > 0) {
                $this->env->popFolderStack();
            }
        } else {
            $subfolders = $this->filesystem->getFolders($this->env->getCurrentFolder());

            $subfolders = array_values(array_filter($subfolders, function ($folder) use ($target) {
                return $folder->getName() === $target;
            }));

            if (empty($subfolders)) {
                return "Folder not found.\n";
            } else {
                $this->env->pushFolderStack($this->env->getCurrentFolder());
                $this->env->setCurrentFolder($subfolders[0]);
            }
        }
    }

    public function getName()
    {
        return 'cd';
    }
}
