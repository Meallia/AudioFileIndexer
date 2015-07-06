<?php

namespace AudioFileIndexer\Media;

class Artist
{
    use NameFromTags;

    protected $name;

    /**
     * @var Album[]
     */
    protected $albums = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Album[]
     */
    public function getAlbums()
    {
        return $this->albums;
    }


    /**
     * @param Album $album
     */
    public function addAlbum($album)
    {
        $this->albums[] = $album;
        $album->setArtist($this);
    }
}