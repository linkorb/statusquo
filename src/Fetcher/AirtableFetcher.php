<?php

namespace StatusQuo\Fetcher;

use StatusQuo\Model\Table;
use StatusQuo\Model\Project;
use StatusQuo\Model\Record;
use GuzzleHttp\Client as GuzzleClient;

class AirtableFetcher
{
    public function fetch(Table $table)
    {
        $apikey = $table->getSourceParameters()['apikey'];
        $appkey = $table->getSourceParameters()['appkey'];
        
        $headers = [
            "Authorization" => "Bearer " . $apikey
        ];
        $this->httpClient = new GuzzleClient(
            [
                'headers' => $headers
            ]
        );
        
        $url = 'https://api.airtable.com/v0/' . $appkey . '/';
        $url .= rawurlencode($table->getSourceParameters()['table']);
        $url .= '?maxRecords=1000';
        $url .= '&view=' . rawurlencode($table->getSourceParameters()['view']);
        $res = $this->httpClient->get(
            $url,
            [
                'headers' => $headers,
            ]
        );
        if ($res->getStatusCode() != 200) {
            throw new RuntimeException("Unexpected statuscode: " . $res->getStatusCode());
        }
        $data = json_decode($res->getBody(), true);
        $records = $this->parseRecordsResponse($table, $data);
        return $records;
    }
    
    public function parseRecordsResponse(Table $table, $data)
    {
        foreach ($data['records'] as $recordData) {
            $record = new Record();
            $record->setTable($table);
            $id = $recordData['id'];
            $tblkey = $table->getSourceParameters()['tblkey'];
            $viwkey = $table->getSourceParameters()['viwkey'];
            $record->setUrl('https://airtable.com/' . $tblkey . '/' . $viwkey . '/' . $id);
            $record->setData('id', $id);
            $record->setData('createdTime', $recordData['createdTime']);
            
            foreach ($recordData['fields'] as $key => $value) {
                if ($table->hasField($key)) {
                    $record->setData($key, $value);
                }
            }
            $records[] = $record;
        }
        return $records;
    }
}
