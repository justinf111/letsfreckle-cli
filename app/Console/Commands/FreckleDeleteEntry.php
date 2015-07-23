<?php

namespace App\Console\Commands;

class FreckleDeleteEntry extends Freckle
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove an entry from letsfreckle';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entries = $this->freckleApi->listEntries();

        if(count($entries) > 0) {
            $name = $this->choice('Which entry do you want to delete?', $this->prepareEntryList(), false);

            $selectedEntry = $this->getSelectedEntry($entries, $name);

            if($this->confirm('Are you sure you wish to delete this entry? [yes|no]')) {
                $this->freckleApi->deleteEntry($selectedEntry->id);
            }

            $totalHours = $this->converter->toHoursAndMinutes($this->freckleApi->getTotalHours(), $this->format);
            $this->comment($totalHours);

            $this->makeNextDecision();
        }
    }
}
