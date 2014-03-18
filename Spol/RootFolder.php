<?php

namespace Spol\FSM;

use LogicException;

class RootFolder extends Folder
{
    public function __construct()
    {
        $this->name = "";
        $this->path = "";
    }

    public function getPath()
    {
        if ($this->path === null) {
            return null;
        } else {
            return '/';
        }
    }

    public function setName($name)
    {
        throw new LogicException("Cannot set the name of the Root folder.");
    }

    public function setPath($name)
    {
        throw new LogicException("Cannot set the path of the Root folder.");
    }
}
