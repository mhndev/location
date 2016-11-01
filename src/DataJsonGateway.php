<?php

namespace mhndev\location;

use mhndev\location\Interfaces\iDataGateway;

/**
 * Class DataJsonGateway
 * @package mhndev\location
 */
class DataJsonGateway implements iDataGateway
{

    /**
     * @var
     */
    protected $dataSource;

    /**
     * @param $query
     * @return mixed
     */
    function query($query)
    {
        $file = file_get_contents($this->dataSource);

        $content = json_decode($file, true)['RECORDS'];

        $results = [];

        foreach ($content as $record){
            similar_text($record['slug'], $query, $similaritySlug);

            if($similaritySlug > 50){
                $results[] = $record;
            }
        }

        if(!empty($results)){
            return $results;
        }

        return false;
    }

    /**
     * @param $dataSource
     * @return $this
     */
    function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;

        return $this;
    }


}
