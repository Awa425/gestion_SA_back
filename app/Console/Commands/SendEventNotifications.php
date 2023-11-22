<?php

namespace App\Console\Commands;

use App\Models\Apprenant;
use App\Models\Evenement;
use Illuminate\Console\Command;
use App\Models\EvenementReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Notifications\SendEventNotification;

class SendEventNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $idEvent=$this->verifieDateEventComing()['id_event'];
        $event=Evenement::where('id',$idEvent)->first();
        if ($this->getAppRefEvent()) {
            $apprenants= $this->getAppRefEvent();
            foreach ($apprenants as $app) {
                $app->notify(new SendEventNotification($event));
            }
            $this->info('Event notifications sent successfully.');
        }
        else{
            $this->info('Failed to send event notifications');
        }
    }
    public function verifieDateEventComing(){
        $events=Evenement::where('is_active',1)->get();
        $notifsEvents=$events->map(function ($event){
            return [
                "notfication_date" => $event->notfication_date,
                "id_event" => $event->id
            ];
        });
   
        foreach ($notifsEvents as $time) {
          
            if (strtotime($time['notfication_date'])==strtotime(explode(" ",now())[0])) {
                return $time;
            }
        }
    }
    public function getAppRefEvent(){
        if ($this->verifieDateEventComing()) {
           $idEvent=$this->verifieDateEventComing()["id_event"];
        }
        $idsPromoRef= EvenementReferentiel::where('evenement_id',$idEvent)->pluck('promo_referentiel_id');
        $promosRefApp= PromoReferentielApprenant::whereIn('promo_referentiel_id',$idsPromoRef)->pluck('apprenant_id');
        return Apprenant::whereIn('id',$promosRefApp)->get();

    }
}
