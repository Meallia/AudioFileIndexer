<?php

namespace AudioFileIndexer\Media\Track;


use AudioFileIndexer\Filesystem\File;
use AudioFileIndexer\Media\Track\FlacTrack;
use AudioFileIndexer\Media\Track\Mp3Track;
use AudioFileIndexer\Media\Track\Track;

class TrackFactory
{
    /**
     * @param File $file
     * @return Track|null
     */
    public function createTrackFromFile(File $file)
    {
        $fileExt = strtolower(pathinfo($file->getPath(), PATHINFO_EXTENSION));

        switch($fileExt){
            case 'mp3':
                return new Mp3Track($file);
                break;
            case 'flac':
                return new FlacTrack($file);
            default:
                return null;
            //TODO: throw exception instead;
        }
    }
}