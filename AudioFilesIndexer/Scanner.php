<?php

namespace AudioFilesIndexer;

use AudioFilesIndexer\Media\Album;
use AudioFilesIndexer\Media\Artist;
use AudioFilesIndexer\Filesystem\Directory;
use AudioFilesIndexer\Media\Disc;

use AudioFilesIndexer\Media\Track\TrackFactory;

class Scanner
{
    const UNKNOWN_ALBUM_NAME = 'UNKNOWN_ALBUM_NAME';

    /**
     * @var Directory[]
     */
    protected $directories;

    /**
     * @var Artist[]
     */
    protected $artists = [];

    /**
     * @var TrackFactory
     */
    protected $trackFactory = null;

    /**
     * @var string[]
     */
    protected $acceptedTypes = [
        'mp3',
        'flac',
        'ogg'
    ];

    public function __construct($directories = [])
    {
        $this->directories = $directories;
    }

    public function scan()
    {
        foreach ($this->getDirectories() as $baseDir) {
            $baseDir->setFileTypeWhiteList($this->getAcceptedTypes());

            $baseDir->explore();

            /**
             * let's assume every top level directory is an artist
             */

            foreach ($baseDir->getDirectories() as $artistDir) {
                if (!$artistDir->isEmpty()) {
                    $artist = $this->getOrCreateArtistFromDirectory($artistDir);
                    $artistPossibleNames = [];

                    /**
                     * now let's assume every 2nd level directory is an album
                     */
                    foreach ($artistDir->getDirectories() as $albumDir) {
                        if (!$albumDir->isEmpty()) {
                            $album = $this->createAndAddAlbumToArtistFromDirectory($artist, $albumDir);
                            $artistPossibleNames[] = $this->populateAlbum($album);
                            foreach ($albumDir->getDirectories() as $discDir) {
                                if (!$discDir->isEmpty()) {
                                    $disc = $this->createAndAddDiscToAlbum($album, $discDir);
                                    $artistPossibleNames[] = $this->populateAlbum($disc);
                                }
                            }
                        }
                    }

                    /**
                     * if there are any files directly in the artist directory, let's create a fake album to add those in
                     */

                    if ($artistDir->getFiles()) {
                        $album = $this->createAndAddAlbumToArtistFromDirectory($artist, $artistDir, self::UNKNOWN_ALBUM_NAME);
                        $artistPossibleNames[] = $this->populateAlbum($album);
                    }
                }
            }
        }
    }

    /**
     * @param Directory $dir
     *
     * @return Artist
     */
    protected function getOrCreateArtistFromDirectory(Directory $dir)
    {
        $candidateArtistName = $dir->getName();
        $sanitizedArtistName = $this->prepareName($candidateArtistName);

        if (isset($this->artists[$sanitizedArtistName])) {
            $artist = $this->artists[$sanitizedArtistName];
        } else {
            $artist = new Artist($candidateArtistName);
            $this->artists[$sanitizedArtistName] = $artist;
        }

        return $artist;
    }

    /**
     * @param Artist $artist
     * @param Directory $dir
     * @param string $name
     * @return Album
     */
    protected function createAndAddAlbumToArtistFromDirectory(Artist $artist, Directory $dir, $name = null)
    {
        $candidateAlbumName = $name ?: $dir->getName();
        $album = new Album($candidateAlbumName, $dir);
        $artist->addAlbum($album);

        return $album;
    }


    /**
     * @param Album $album
     * @param Directory $discDir
     *
     * @return Disc
     */
    protected function createAndAddDiscToAlbum(Album $album, Directory $discDir)
    {
        $candidateAlbumName = $discDir->getName();
        $disc = new Disc($candidateAlbumName, $discDir);
        $album->addDisc($disc);

        return $disc;
    }

    protected function prepareName($string)
    {

        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r',
        );

        return trim(strtolower(strtr($string, $table)));

    }


    /**
     * @return Directory[]
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * @param Directory $directory
     */
    public function addDirectory($directory)
    {
        $this->directories[] = $directory;
    }

    /**
     * @return Artist[]
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * @param Album $album
     *
     */
    private function populateAlbum(Album $album)
    {
        foreach($album->getDirectory()->getFiles() as $file) {
            $track = $this->getTrackFactory()->createTrackFromFile($file);
            $album->addTrack($track);
        }
    }

    /**
     * @return TrackFactory
     */
    public function getTrackFactory()
    {
        if ( null == $this->trackFactory ) {
            $this->trackFactory = new TrackFactory();
        }

        return $this->trackFactory;
    }

    /**
     * @return string[]
     */
    public function getAcceptedTypes()
    {
        return $this->acceptedTypes;
    }

    /**
     * @param string[] $acceptedTypes
     * @return $this
     */
    public function setAcceptedTypes($acceptedTypes)
    {
        $this->acceptedTypes = $acceptedTypes;
        return $this;
    }

}