<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PromoInactivesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->formatPromo($this->collection),
        ];
    }

    public function formatPromo($collection){
        return $collection->map(function($promo){
            return [
                'promo' => $promo,
                'nbreFille'=>$this->getNbrAppByPromoIdAndGenre('Feminin',$promo->id),
                'nbreGarcon'=>$this->getNbrAppByPromoIdAndGenre('Masculin',$promo->id),
                'nbreApprenant'=>$this->getNumAppByPromo($promo->id)
            ];
        });
    }

    public function getNbrAppByPromoIdAndGenre($genre, $idPromo)
    {
        $idPromoRef= PromoReferentiel::where('promo_id', $idPromo)->pluck('id');

        return PromoReferentielApprenant::whereIn('promo_referentiel_id',$idPromoRef)
                ->join('apprenants','apprenants.id','=','promo_referentiel_apprenants.apprenant_id')
                ->where('genre',$genre)
                ->get()
                ->count();
    }
    public function getNumAppByPromo($idPromo){
        $idPromoRef= PromoReferentiel::where('promo_id', $idPromo)->pluck('id');
        return PromoReferentielApprenant::whereIn('promo_referentiel_id',$idPromoRef)
                ->join('apprenants','apprenants.id','=','promo_referentiel_apprenants.apprenant_id')
                ->get()
                ->count();
    }

}
