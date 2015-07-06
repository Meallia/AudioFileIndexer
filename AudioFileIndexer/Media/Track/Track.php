<?php

namespace AudioFileIndexer\Media\Track;

use AudioFileIndexer\Filesystem\File;
use AudioFileIndexer\Media\Album;

use \GetId3\GetId3Core as GetId3;

abstract class Track
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $artistName = '';
    /**
     * @var string
     */
    protected $albumName = '';
    /**
     * @var int
     */
    protected $trackTotal = 0;
    /**
     * @var int
     */
    protected $trackNumer = 0;

    /**
     * @var int
     */
    protected $length = 0;
    /**
     * @var int
     */
    protected $bitRate = 0;

    /**
     * @var string
     */
    protected $date = '';

    /**
     * @var File
     */
    protected $file;

    /**
     * @var Album
     */
    protected $album;

    /**
     * @var GetId3
     */
    protected $id3;

    function __construct(File $file)
    {
        $this->file = $file;
        $this->name = pathinfo($file->getPath(), PATHINFO_BASENAME);

        $this->processMetaData();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTrackNumer()
    {
        return $this->trackNumer;
    }

    /**
     * @param mixed $trackNumer
     */
    public function setTrackNumer($trackNumer)
    {
        $this->trackNumer = $trackNumer;
    }

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return mixed
     */
    public function getTrackTotal()
    {
        return $this->trackTotal;
    }

    /**
     * @param mixed $trackTotal
     */
    public function setTrackTotal($trackTotal)
    {
        $this->trackTotal = $trackTotal;
    }

    /**
     * @return mixed
     */
    public function getBitRate()
    {
        return $this->bitRate;
    }

    /**
     * @param mixed $bitRate
     */
    public function setBitRate($bitRate)
    {
        $this->bitRate = $bitRate;
    }

    /**
     * @return mixed
     */
    public function getArtistName()
    {
        return $this->artistName;
    }

    /**
     * @param mixed $artistName
     */
    public function setArtistName($artistName)
    {
        $this->artistName = $artistName;
    }

    /**
     * @return mixed
     */
    public function getAlbumName()
    {
        return $this->albumName;
    }

    /**
     * @param mixed $albumName
     */
    public function setAlbumName($albumName)
    {
        $this->albumName = $albumName;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    protected abstract function processMetaData();

    /**
     * @return GetId3
     */
    protected function getId3()
    {
        if ($this->id3 === null) {
            $this->id3 = new GetId3();
            $this->id3
                ->setOptionMD5Data(true)
                ->setOptionMD5DataSource(true)
                ->setEncoding('UTF-8');
        }

        return $this->id3;
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
       return $this->getId3()->analyze($this->getFile()->getPath());
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}