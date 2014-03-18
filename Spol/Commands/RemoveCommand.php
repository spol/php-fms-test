<?php

namespace Spol\FSM\Commands;

use Spol\FSM\Folder;

class RemoveCommand extends Command
{
    public function main($argv, $argc)
    {
        if ($argc < 2) {
            return "No name specified for new file.";
        }

        $item = $this->findChild($this->env->getCurrentFolder(), $argv[1]);

        if ($item == null) {
            return "Item doesn't exist.\n";
        } elseif ($item instanceof Folder) {
            $this->filesystem->deleteFolder($item);
        } else {
            $this->filesystem->deleteFile($item);
        }
        return "";
    }

    public function getName()
    {
        return 'rm';
    }
}
