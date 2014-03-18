<?php

namespace Spol\FSM\Commands;

use Spol\FSM\Folder;

class UsageCommand extends Command
{
    public function main($argv, $argc)
    {
        $size = $this->filesystem->getDirectorySize($this->env->getCurrentFolder());

        return $this->formatBytes($size) . PHP_EOL;
    }

    public function getName()
    {
        return 'du';
    }

    protected function formatBytes($bytes)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . $units[$pow];
    }
}
