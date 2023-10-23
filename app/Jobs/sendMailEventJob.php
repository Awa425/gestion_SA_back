<?php

namespace App\Jobs;

use App\Models\Evenement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class sendMailEventJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $evenement;
    /**
     * Create a new job instance.
     */
    public function __construct(Evenement $evenement)
    {
        $this->evenement = $evenement;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
       $this->evenement->evenement_referentiels();
    }
}
