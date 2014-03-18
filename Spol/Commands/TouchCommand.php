<?php

namespace Spol\FSM\Commands;

use Spol\FSM\File;
use Spol\FSM\AlreadyExistsException;

class TouchCommand extends Command
{
    public function main($argv, $argc)
    {
        if ($argc < 2) {
            return "No name specified for new file.";
        }

        $item = $this->findChild($this->env->getCurrentFolder(), $argv[1]);

        if ($item === null) {
            $file = new File($argv[1], 0);
            if ($argc > 2) {
                if (!is_numeric($argv[2])) {
                    return "Invalid size specified.";
                } else {
                    $file->setSize((int)$argv[2]);
                }
            }
            $this->filesystem->createFile($file, $this->env->getCurrentFolder());
            return "";
        } elseif ($item instanceof File) {
            if ($argc > 2) {
                if (!is_numeric($argv[2])) {
                    return "Invalid size specified.";
                } else {
                    $item->setSize((int)$argv[2]);
                }
            }
            $this->filesystem->updateFile($item);
            return "";
        } else {
            return "Touching a directory is not allowed.\n";
        }
    }

    public function getName()
    {
        return 'touch';
    }
}
