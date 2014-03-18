<?php

namespace Spol\FSM\Commands;

class CommandDispatcher
{
    protected $commands;

    public function register(Command $command)
    {
        $this->commands[$command->getName()] = $command;
    }

    public function dispatch($line)
    {
        $segments = array_filter(explode(' ', trim($line)), function ($segment) {
            return $segment !== '';
        });

        if (array_key_exists($segments[0], $this->commands)) {
            return $this->commands[$segments[0]]->main($segments, count($segments));
        } else {
            return "Command not found.\n";
        }

    }
}
