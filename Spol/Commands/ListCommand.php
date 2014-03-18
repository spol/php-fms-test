<?php

namespace Spol\FSM\Commands;

class ListCommand extends Command
{
    public function main($argv, $argc)
    {
        $folders = $this->filesystem->getFolders($this->env->getCurrentFolder());
        $files = $this->filesystem->getFiles($this->env->getCurrentFolder());

        $output = "total " . (count($folders) + count($files)) . PHP_EOL;

        foreach ($folders as $folder) {
            $output .= sprintf(
                "D    0 %s %s\n",
                $folder->getCreatedTime()->format('d M H:i'),
                $folder->getName()
            );
        }

        foreach ($files as $file) {
            $output .= sprintf(
                "  % 4d %s %s\n",
                $file->getSize(),
                $file->getModifiedTime()->format('d M H:i'),
                $file->getName()
            );
        }

        return $output;
    }

    public function getName()
    {
        return 'ls';
    }
}
