<?php

namespace AudioFilesIndexer\Media\Track;


class Mp3Track extends Track
{

    protected function processMetaData()
    {
        $metaData = $this->getMetaData();
        if (isset($metaData['error'])) {
            return $this;
        }
        $id3tags = $metaData['tags'];

        if (isset($id3tags['id3v2'])) {
            foreach ($id3tags['id3v2'] as $name => $comment) {
                switch (strtolower($name)) {
                    case 'album':
                        $this->setAlbumName($comment[0]);
                        break;

                    case 'artist':
                        $this->setArtistName($comment[0]);
                        break;

                    case 'year':
                        $this->setDate($comment[0]);
                        break;

                    case 'title':
                        $this->setName($comment[0]);
                        break;

                    case 'track_number':
                        if (preg_match('/([0-9]+)\s*\/\s*([0-9]+)/', $comment[0], $matches)) {
                            $this->setTrackNumer($matches[1]);
                            $this->setTrackTotal($matches[2]);
                        }
                        $this->setTrackNumer($comment[0]);
                        break;
                }
            }
        } else if (isset($id3tag['id3v1'])) {
            foreach ($id3tags['id3v1'] as $name => $comment) {
                switch (strtolower($name)) {
                    case 'year':
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


                    case 'track_number':
                        if (preg_match('/([0-9]+)\s*/\s*([0-9]+)/', $comment[0], $matches)) {
                            $this->setTrackNumer($matches[1]);
                            $this->setTrackTotal($matches[2]);
                        }
                        $this->setTrackNumer($comment[0]);
                        break;
                }
            }
        }

        return $this;
    }
}