<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FreckleApi;
use App\Services\Converter;


class FreckleUpdateEntry extends Freckle
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edit todays entries';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entries = $this->freckleApi->listEntries();

        if(count($entries) > 0) {
            $name = $this->choice('Which entry do you want to edit?', $this->prepareEntryList(), false);

            $selectedEntry = $this->getSelectedEntry($entries, $name);
            $time = $this->ask('How long have you worked on this project for (HH:MM)?', $selectedEntry->minutes);

            $minutes = $this->converter->toMinutes($time);

            $description = $this->ask('Please write a description for this entry.');

            if($this->confirm('You have worked on '.$name.' for '.$minutes.' doing '.$description.'. Do you wish to update this entry? [yes|no]')) {
                $this->freckleApi->editEntry($selectedEntry->id, $minutes, $description);
            }

            $totalHours = $this->converter->toHoursAndMinutes($this->freckleApi->getTotalHours(), $this->format);
            $this->comment($totalHours);

            $this->makeNextDecision();
        }
    }
}
