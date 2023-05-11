<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Presence;

class AbsenceInsertion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:absence-insertion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'insertion des absents';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $presence = Presence::where('date_heure_arriver', Carbon::now())->first();

        if (!$presence) {
            $presence = new Presence();
            $presence->date_heure_arriver = Carbon::now();
            $presence->save();
        }

        $this->info('La commande bien executer');
    }
}
