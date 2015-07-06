<?php

namespace AudioFilesIndexer\Media\Track;


class FlacTrack extends Track
{

    protected function processMetaData()
    {

        $metaData = $this->getMetaData();
        if (isset($metaData['error'])) {
            return $this;
        }
        $vorbisComment = $metaData['tags']['vorbiscomment'];

        foreach($vorbisComment as $name => $comment) {
            switch(strtolower($name)) {
                case 'album':
                    $this->setAlbumName($comment[0]);
                    break;

                case 'artist':
                    $this->setArtistName($comment[0]);
                    break;

                case 'date':
                    $this->setDate($comment[0]);
                    break;

                case 'title':
                    $this->setName($comment[0]);
                    break;

                case 'tracktotal':
                    $this->setTrackTotal($comment[0]);
                    break;

                case 'tracknumber':
                    $this->setTrackNumer($comment[0]);
                    break;
            }
        }

        return $this;
    }
}