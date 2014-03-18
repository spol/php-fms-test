<?php

namespace Spol\FSM;

use FileInterface;
use FolderInterface;
use InvalidArgumentException;
use DateTime;

class File extends FilesystemEntry implements FileInterface
{

    protected $size;

    protected $modified;

    protected $parent;

    public function __construct($name, $size = 0)
    {
        $this->setName($name);
        $this->setSize($size);
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return $this
     */
    public function setSize($size)
    {
        if (!is_int($size)) {
            throw new InvalidArgumentException("Specified size is not an int.");
        }

        $this->size = $size;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getModifiedTime()
    {
        return $this->modified;
    }

    /**
     * @param DateTime $modified
     *
     * @return $this
     */
    public function setModifiedTime($modified)
    {
        if (!($modified instanceof DateTime)) {
            throw new InvalidArgumentException("Argument not an instance of DateTime class.");
        }

        $this->modified = $modified;
        return $this;
    }

    /**
     * @return FolderInterface
     */
    public function getParentFolder()
    {
        return $this->parent;
    }

    /**
     * @param FolderInterface $parent
     *
     * @return $this
     */
    public function setParentFolder(FolderInterface $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getParentFolder()->getPath() . $this->getName();
    }
}
