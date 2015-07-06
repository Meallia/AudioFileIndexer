<?php

namespace AudioFileIndexer\Media;

use AudioFileIndexer\Filesystem\Directory;
use AudioFileIndexer\Media\Track\Track;

class Album
{
    use NameFromTags;
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Artist
     */
    protected $artist;

    /**
     * @var Track[]
     */
    protected $tracks = [];

    /**
     * @var Disc[]
     */
    protected $discs = [];


    /**
     * @var Directory
     */
    protected $directory;

    function __construct($name, Directory $directory)
    {
        $this->name = $name;
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param Artist $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return Track[]
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @param Track $track
     */
    public function addTrack($track)
    {
        $this->tracks[] = $track;
        $track->setAlbum($this);
    }

    /**
     * @return Directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @return Disc[]
     */
    public function getDiscs()
    {
        return $this->discs;
    }

    /**
     * @param Disc $disc
     */
    public function addDisc($disc)
    {
        $this->discs[] = $disc;
        $disc->setAlbum($this);
        $disc->setArtist($this->getArtist());
    }


}