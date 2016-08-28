<?php

namespace StatusQuo\Fetcher;

use StatusQuo\Model\Table;
use StatusQuo\Model\Project;
use StatusQuo\Model\Record;
use GuzzleHttp\Client as GuzzleClient;

class ListbaseFetcher
{
    public function fetch(Table $table)
    {
        $username = $table->getSourceParameters()['username'];
        $password = $table->getSourceParameters()['password'];
        $accountName = $table->getSourceParameters()['accountname'];
        $boardName = $table->getSourceParameters()['boardname'];
        $headers = [];
        $this->httpClient = new GuzzleClient(
            [
                'headers' => $headers,
                'auth' => [
                    $username,
                    $password
                ]
            ]
        );
        
        $url = 'https://listbase.io/api/v1/' . $accountName . '/' . $boardName . '/cards';
        $res = $this->httpClient->get(
            $url,
            [
                'headers' => $headers,
                'auth' => [
                    $username,
                    $password
                ]
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
        foreach ($data as $recordData) {
            $record = new Record();
            $id = $recordData['id'];
            $record->setTable($table);
            $record->setUrl('https://listbase.io/cards/' . $id);
            $record->setData('id', $id);
            $record->setData('createdTime', $recordData['created_at']);
            $record->setData('Name', $recordData['name']);
            $record->setData('Description', $recordData['description']);
            $record->setData('Details', $recordData['details']);
            foreach ($recordData['card_boards'] as $cardBoardData) {
                //TODO: Support board-types in listbase!
                if (substr($cardBoardData['board']['name'], 0, 5)=='user-') {
                    $record->setData('Assignee', substr($cardBoardData['board']['name'], 5));
                } else {
                    $record->setData('Project', $cardBoardData['board']['name']);
                    $record->setData('Status', $cardBoardData['list']['name']);
                }
            }
            $records[] = $record;
        }
        //var_dump($records); exit();
        return $records;
    }
}
