<?php

namespace AudioFileIndexer\Media;


trait NameFromTags
{
    protected $possibleNames = [];


    /**
     * @param string $name
     * @return $this
     */
    public function addPossibleName($name)
    {
        if ( !isset($this->possibleNames[$name])) {
            $this->possibleNames[$name] = 0;
        }

        $this->possibleNames[$name] += 1;

        asort($this->possibleNames, SORT_NUMERIC);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMostLikelyName()
    {
        if ( empty($this->possibleNames) ) {
            return null;
        }    else {
            return array_keys($this->possibleNames)[0];
        }
    }



}