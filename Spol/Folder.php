<?php

namespace Spol\FSM;

use FolderInterface;
use InvalidArgumentException;
use DateTime;

class Folder extends FilesystemEntry implements FolderInterface
{
    protected $path;

    public function __construct($name)
    {
        parent::setName($name);
    }

        /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        if ($this->getPath() !== null) {
            $segments = explode("/", $this->getPath());

            $segments[count($segments)-2] = $name;

            $this->setPath(implode('/', $segments));
        } else {
            parent::setName($name);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        if ($this->path === null) {
            return null;
        } else {
            return $this->path;
        }
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        if (!$this->validatePath(rtrim($path, '/'))) {
            throw new InvalidArgumentException("Invalid path specified.");
        }

        $segments = explode('/', $path);
        array_pop($segments);

        $name = $segments[count($segments)-1];


        parent::setName($name);

        $this->path = $path;

        return $this;
    }
}
