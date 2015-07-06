<?php

namespace AudioFilesIndexer\Media\Track;


use AudioFilesIndexer\Filesystem\File;
use AudioFilesIndexer\Media\Track\FlacTrack;
use AudioFilesIndexer\Media\Track\Mp3Track;
use AudioFilesIndexer\Media\Track\Track;

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