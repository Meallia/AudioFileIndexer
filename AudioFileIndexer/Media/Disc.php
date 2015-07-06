<?php

namespace AudioFileIndexer\Media;


class Disc extends Album
{

    /**
     * @var Album
     */
    protected $album = null;

    /**
     * @return Album
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param Album $album
     * @return $this
     */
    public function setAlbum(Album $album)
    {
        $this->album = $album;

        return $this;
    }
} 