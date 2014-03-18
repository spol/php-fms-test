<?php

namespace Spol\FSM\Commands;

class HelpCommand extends Command
{
    public function main($argv, $argc)
    {
        return <<<EOL
Available commands:
    help - Display this list of commands
    ls - List files in current folder.
    cd [name] - Change directory
    quit - Quit
    mkdir [name] - Create a folder.
    touch [name] [size] - Create or update a file. (Size is optional.)
    rm [name] - delete file or folder.
    du - get the size of all files in the current directory and subdirectories.
    rename [source] [dest] - Rename a file or directory.
EOL;
    }

    public function getName()
    {
        return "help";
    }
}
