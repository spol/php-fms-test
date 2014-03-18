<?php

namespace Spol\FSM\Commands;

use Spol\FSM\Folder;
use Spol\FSM\AlreadyExistsException;

class MakeDirCommand extends Command
{
    public function main($argv, $argc)
    {
        if ($argc < 2) {
            return "No name specified for new directory.";
        }

        try {
            $this->filesystem->createFolder(new Folder($argv[1]), $this->env->getCurrentFolder());
        } catch (AlreadyExistsException $exp) {
            return "A file or directory already exists with that name.\n";
        }

        return "";
    }

    public function getName()
    {
        return 'mkdir';
    }
}
