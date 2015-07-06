<?php

namespace AudioFilesIndexer\Filesystem;

use FilesystemIterator;

class Directory extends File
{

    /**
     * @var string[]
     */
    protected $fileTypeWhiteList = [];

    /**
     * @var File[]
     */
    protected $files = [];

    /**
     * @var Directory[]
     */
    protected $directories = [];

    /**
     * @return Directory[]
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @var bool
     */
    protected $explored = false;

    /**
     * @param File $file
     *
     * @return $this
     */
    public function addFile(File $file)
    {
        if (!in_array($file, $this->getFiles(), false)) {
            $this->files[$file->getName()] = $file;
            $file->setParent($this);
        }

        return $this;
    }

    /**
     * @param string $path
     * @return File
     */
    public function addFileByPath($path)
    {
        $file = new File($path);
        $this->addFile($file);

        return $file;
    }

    /**
     * @param $path
     * @param bool $explore
     * @return Directory
     */
    public function addDirectoryByPath($path, $explore = true)
    {
        $dir = new Directory($path);
        $dir->setFileTypeWhiteList($this->getFileTypeWhiteList());

        if ($explore) {
            $dir->explore();
        }
        $this->addDirectory($dir);

        return $dir;
    }


    /**
     * @param Directory $dir
     *
     * @return $this
     */
    public function addDirectory(Directory $dir)
    {
        if (!in_array($dir, $this->getDirectories(), false)) {
            $this->directories[$dir->getName()] = $dir;
            $dir->setParent($this);
        }

        return $this;
    }

    public function explore()
    {
        if (!$this->isExplored()) {
            $this->isExplored();

            $fsIterator = new FilesystemIterator($this->getPath(), FilesystemIterator::SKIP_DOTS);

            /** @var FilesystemIterator $fileInfo */
            foreach ($fsIterator as $fileInfo) {

                if ($fileInfo->isFile() && (!$this->getFileTypeWhiteList() || in_array(pathinfo($fileInfo->getRealPath(), PATHINFO_EXTENSION), $this->getFileTypeWhiteList()))) {
                    $this->addFileByPath($fileInfo->getRealPath());
                }

                if ($fileInfo->isDir()) {
                    $this->addDirectoryByPath($fileInfo->getRealPath());
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty() {
        return empty($this->files) && empty($this->directories);
    }

    /**
     * @return boolean
     */
    public function isExplored()
    {
        return $this->explored;
    }

    /**
     * @param boolean $explored
     */
    public function setExplored($explored = true)
    {
        $this->explored = $explored;
    }

    /**
     * @return string[]
     */
    public function getFileTypeWhiteList()
    {
        return $this->fileTypeWhiteList;
    }

    /**
     * @param string[] $fileTypeWhiteList
     */
    public function setFileTypeWhiteList($fileTypeWhiteList)
    {
        $this->fileTypeWhiteList = $fileTypeWhiteList;
    }

}