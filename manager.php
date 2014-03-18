<?php

namespace Spol\FSM;

use Spol\FSM\Commands\Environment;
use Spol\FSM\Commands\CommandDispatcher;
use Spol\FSM\Commands\HelpCommand;
use Spol\FSM\Commands\ListCommand;
use Spol\FSM\Commands\MakeDirCommand;
use Spol\FSM\Commands\ChangeDirCommand;
use Spol\FSM\Commands\TouchCommand;
use Spol\FSM\Commands\RemoveCommand;
use Spol\FSM\Commands\UsageCommand;
use Spol\FSM\Commands\RenameCommand;
use Exception;

require "vendor/autoload.php";

$config = Config::fromFile('fsm.ini');

try {
    $database = new Database($config->get('mysql'));
} catch (Exception $exp) {
    die("Unable to connect to the database.\n");
}

$filesystem = new FileSystem($database);

$root = new RootFolder();
$filesystem->createRootFolder($root);

$env = new Environment();
$env->setCurrentFolder($root);

$commandDispatcher = new CommandDispatcher();
$commandDispatcher->register(new HelpCommand($filesystem, $env));
$commandDispatcher->register(new ListCommand($filesystem, $env));
$commandDispatcher->register(new MakeDirCommand($filesystem, $env));
$commandDispatcher->register(new ChangeDirCommand($filesystem, $env));
$commandDispatcher->register(new TouchCommand($filesystem, $env));
$commandDispatcher->register(new RemoveCommand($filesystem, $env));
$commandDispatcher->register(new UsageCommand($filesystem, $env));
$commandDispatcher->register(new RenameCommand($filesystem, $env));

while (true) {
    $line = readline("FSM: {$env->getCurrentFolder()->getPath()}> ");
    readline_add_history($line);

    if (trim($line) === "quit" || trim($line) === "exit") {
        echo "Quitting...\n";
        break;
    } else {
        echo $commandDispatcher->dispatch($line);
    }
}
