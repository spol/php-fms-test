<?php

namespace Spol\FSM;

use InvalidArgumentException;
use DateTime;

abstract class FilesystemEntry
{
    protected $id;
    protected $name;
    protected $created;

    protected $nameRegex = "#^[^\\/:]+$#";

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        if (!$this->validateName($name)) {
            throw new InvalidArgumentException("Invalid name provided.");
        }

        $this->name = $name;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     *
     * @return $this
     */
    public function setCreatedTime($created)
    {
        if (!($created instanceof DateTime)) {
            throw new InvalidArgumentException("Argument not an instance of DateTime class.");
        }

        $this->created = $created;
        return $this;
    }

    protected function validateName($name)
    {
        return preg_match($this->nameRegex, $name) > 0;
    }

    protected function validatePath($path)
    {
        $path = explode('/', $path);

        if ($path[0] === "") {
            array_shift($path);
        }

        foreach ($path as $pathSegment) {
            if (!$this->validateName($pathSegment)) {
                return false;
            }
        }
        return true;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
