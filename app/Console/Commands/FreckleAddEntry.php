<?php

namespace App\Console\Commands;

class FreckleAddEntry extends Freckle
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-entry';

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
        $name = $this->choice('What project are you working on?', $this->prepareProjectList(), false);
        $time = $this->ask('How long have you worked on this project for (HH:MM)?');

        $minutes = $this->converter->toMinutes($time);

        $description = $this->ask('Please write a description for this entry.');

        if($this->confirm('You have worked on '.$name.' for '.$minutes.' doing '.$description.'. Do you wish to submit this entry? [yes|no]')) {
            $this->freckleApi->submitEntry($name, $minutes, $description);
        }

        $totalHours = $this->converter->toHoursAndMinutes($this->freckleApi->getTotalHours(), $this->format);
        $this->comment($totalHours);

        $this->makeNextDecision();
    }
}
