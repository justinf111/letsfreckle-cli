<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FreckleApi;


class FreckleEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Submit an entry to letsfreckle';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $freakleApi = new FreckleApi();
        $projects = $freakleApi->listProjects();
        $projectList = [];
        foreach($projects as $project) {
            $projectList[] = $project->name;
        }
        $name = $this->choice('What project are you working on?', $projectList, false);
        $minutes = $this->ask('How long have you worked on this project for (HH:MM)?');
        $description = $this->ask('Please write a description for this entry.');

        if($this->confirm('You have worked on '.$name.' for '.$minutes.' doing '.$description.'. Do you wish to submit this entry? [yes|no]')) {
            $freakleApi->submitEntry($name, $minutes, $description);
        }
    }
}
