<?php

namespace App\Services;

use GuzzleHttp\Client as client;

class FreckleApi {

    public $freckle_token = "9gio0xz85hx5qnqfjqrtd9awciqfx0y-h5i9ytx92lqcp1x6v6fzhui9rs1f0yf";

    public function __construct(){

    }

    public function listProjects() {
        $result = $this->request('projects');
        return $result;
    }

    public function submitEntry($projectId, $minutes, $description) {
        $date = new \DateTime(); //this returns the current date time
        $dateFormat = $date->format('Y-m-d');
        $data = [
            'project_id' => $projectId,
            'minutes' => $minutes,
            'description' => $description,
            'date' => $dateFormat
        ];
        var_dump($data);
        $result = $this->request('entries', $data);
        return $result;
    }

    public function request($url, $query = []) {
        $client = new client(['defaults' => [
            'verify' => false
        ]]);
        $query['freckle_token'] = $this->freckle_token;

        $res = $client->get('https://api.letsfreckle.com/v2/'.$url, ['query' => $query]);
        $result = $res->getBody()->getContents();
        return json_decode($result);
    }
}