<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FreckleApi;
use App\Services\Converter;

abstract class Freckle extends Command
{
    protected $freckleApi;
    protected $converter;
    protected $format = 'g:i';

    public function __construct() {
        parent::__construct();
        $this->freckleApi = new FreckleApi();
        $this->converter = new Converter();
    }

    public abstract function handle();

    public function prepareProjectList() {
        $projects = $this->freckleApi->listProjects();
        $projectList = [];
        foreach($projects as $project) {
            $projectList[] = $project->name;
        }
        return $projectList;
    }

    public function prepareEntryList() {
        $entries = $this->freckleApi->listEntries();
        $entryList = [];
        foreach($entries as $entry) {
            $entryList[] = $this->createEntryLabel($entry);
        }
        return $entryList;
    }

    public function getSelectedEntry($entries, $name) {
        $selectedEntry = '';
        foreach($entries as $entry) {
            if($this->createEntryLabel($entry) == $name) {
                $selectedEntry = $entry;
                break;
            }
        }
        return $selectedEntry;
    }

    public function createEntryLabel($entry) {
        return $entry->project->name.' '.$entry->minutes.' '.$entry->description;
    }

    public function makeNextDecision() {
        $options = ['Add Entry', 'Edit Entry', 'Delete Entry','Exit'];
        $decision = $this->choice('What do you want to do now?', $options);
        if($decision === 'Edit Entry') {
            $this->call('update-entry');
        }
        elseif($decision === 'Delete Entry') {
            $this->call('delete-entry');
        }
        elseif($decision === 'Add Entry') {
            $this->call('entry');
        }
    }
}
