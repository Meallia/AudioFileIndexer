<?php

namespace AudioFileIndexer\Filesystem;

class File
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Directory
     */
    protected $parent = null;

    public function __construct($path)
    {
        $this->path = $path;
        $this->name = pathinfo($path, PATHINFO_FILENAME );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Directory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Directory $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }


}