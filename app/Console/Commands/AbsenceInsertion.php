<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AbsenceController;
use Illuminate\Http\Request;

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
        $absenceController = new AbsenceController();
        $request = new Request();

        $absenceController->store($request);

        $this->info('La commande a été exécutée avec succès.');
    }
}
