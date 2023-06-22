<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Presence;
use App\Models\Absence;
use App\Models\Apprenant;
use App\Models\ApprenantPresence;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use Illuminate\Http\Response;
use App\Http\Resources\AbsenceResource;
use App\Http\Resources\ApprenantResource;
use App\Http\Resources\AbsenceCollection;
use App\Http\Requests\AbsenceStoreRequest;
use App\Http\Requests\AbsenceUpdateRequest;
class AbsenceController extends Controller
{
    public function index(Request $request)
    {

        $date = !empty($request->input('date')) ? $request->input('date') : Carbon::today();

        $absence = Absence::whereDate('date_absence', $date)
                            ->with(['apprenant'])
                            ->get();
        return new AbsenceCollection($absence);
    }

    
    public function getAbsences(Request $request)
    {

        $promoReferentiel = PromoReferentiel::where('promo_id', $request->promo_id)
        ->where('referentiel_id', $request->referentiel_id)
        ->first();

        $apprenantIds = PromoReferentielApprenant::where('promo_referentiel_id', $promoReferentiel->id)
            ->pluck('apprenant_id');

        $date = !empty($request->input('date')) ? $request->input('date') : Carbon::yesterday();
        $absences = Absence::whereIn('apprenant_id', $apprenantIds)
        ->filter()                
        ->get();

        
        return new AbsenceCollection($absences);
    }

    public function getAbsence(Request $request)
    {

        
       
        $absence = Absence::where('id', $request->id)
        ->filter()                
        ->first();

        
        return new AbsenceResource($absence);
    }




    public function store(Request $request)
    {
        // $Apprenantpresent=ApprenantPresence::where('date_absence', Carbon::today())->get('apprenant_id');
        $ApprenantNotPresent = Apprenant::whereNotIn('id', function ($query) {
            $query->select('apprenant_id')
                ->from('apprenant_presence')
                ->where([
                    ['created_at', Carbon::today()]
                ]);
        })->where('is_active', 1)->pluck('id');


        if ($ApprenantNotPresent) {
            foreach($ApprenantNotPresent as $apprenant){
                $absence['date_absence'] = Carbon::today();
                $absence['apprenant_id'] = $apprenant;
                 $absences = Absence::create($absence);
            }
            return response()->json(['Done' => 'Liste des absence insérer'], 202);;  
        }

        return response()->json(['Done' => 'Tous les apprenants sont présent'], 202);
        
        


       
    }




    public function show(Request $request, Absence $absence)
    {
        return new AbsenceResource($absence);
    }

    public function update(AbsenceUpdateRequest $request, Absence $absence)
    {

        $absence->update([
            'justifier' => 1,

            'motif' => $request->motif,
        ]);

        return new AbsenceResource($absence);
    }

    public function destroy(Request $request, Absence $absence)
    {



        return response()->noContent();
    }

}
