<?php

namespace App\Services;

use GuzzleHttp\Client as client;

class FreckleApi {

    public function __construct(){
        $this->freckleToken = config('app.freckleToken');
        $this->userId = config('app.freckleUserId');
    }

    public function listProjects() {
        $result = $this->request('projects', 'get');
        return $result;
    }

    public function listEntries() {
        $date = $this->currentDate();

        $data = [
            'from' => $date,
            'user_ids' => $this->userId
        ];

        $result = $this->request('entries', 'get', $data);
        return $result;
    }

    public function submitEntry($projectName, $minutes, $description) {
        $date = $this->currentDate();

        $data = [
            'project_name' => $projectName,
            'minutes' => $minutes,
            'description' => $description,
            'date' => $date
        ];

        $result = $this->request('entries', 'post', [], $data);
        return $result;
    }

    public function editEntry($id, $minutes, $description) {

        $data = [
            'minutes' => $minutes,
            'description' => $description,
        ];

        $result = $this->request('entries/'.$id, 'put', [], $data);
        return $result;
    }

    public function deleteEntry($id) {
        $result = $this->request('entries/'.$id, 'delete');
        return $result;
    }

    public function request($url, $method, $query = [], $body = []) {
        $client = new client(['defaults' => [
            'verify' => false
        ]]);

        $query['freckle_token'] = $this->freckleToken;
        $res = $client->{$method}('https://api.letsfreckle.com/v2/'.$url, ['query' => $query, 'body' => json_encode($body)]);

        $result = $res->getBody()->getContents();
        return json_decode($result);
    }

    public function getTotalHours() {
        $entries = $this->listEntries();
        $minutes = 0;
        foreach($entries as $entry) {
            $minutes += $entry->minutes;
        }
        return $minutes;
    }

    public function currentDate() {
        $date = new \DateTime(); //this returns the current date time
        $dateFormat = $date->format('Y-m-d');
        return $dateFormat;
    }
}