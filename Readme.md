# AudioFileIndexer

## example : 

```
$musicDir = new Directory('/path/to/directory');

$scanner = new Scanner();

$scanner->addDirectory($musicDir);

$scanner->setAcceptedTypes(['flac', 'mp3']);

$scanner->scan();

$artists = $scanner->getArtists();

ksort($artists);

foreach($artists as $artist){
    echo sprintf("%s\n", $artist->getName());
    foreach($artist->getAlbums() as $album) {
        echo sprintf("\t%s\n", $album->getName());
        foreach($album->getDiscs() as $disc) {
            echo sprintf("\t\t%s\n", $disc->getName());
            foreach($disc->getTracks() as $track) {
                echo sprintf("\t\t\t%s\n", $track->getName());
            }
        }
        foreach($album->getTracks() as $track) {
            echo sprintf("\t\t%s\n", $track->getName());
        }
    }
}
```

