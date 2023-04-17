<?php

namespace App\Http\Requests\import;

use App\Models\Promo;
use App\Models\Apprenant;
use App\Models\Referentiel;
use Carbon\Carbon;
use App\Models\PromoReferentiel;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Controllers\ApprenantController;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ApprenantsImport implements ToModel, WithHeadingRow
{

    private $promoId;
    private $referentielId;
    private $password;
    private $promo;
    private $referentiel;
    private $referentiel_libelle ;
    private $promo_libelle;

    public function __construct()
    {
        $this->promoId = request()->promo_id;
        $this->referentielId = request()->referentiel_id;
        $this->password = bcrypt('Passer');
        $this->promo = Promo::find($this->promoId);
        $this->referentiel = Referentiel::find($this->referentielId);
        $this->promo_libelle = $this->promo->libelle;
        $this->referentiel_libelle = $this->referentiel->libelle;
    }
   
    public function model(array $row)
    {

        
      
        $matricule=ApprenantController::generate_matricule($this->promo_libelle,$this->referentiel_libelle);
        $date_naissance = Carbon::parse($row['date_naissance'])->toDateString();


        $apprenant = new Apprenant([
            'matricule' => $matricule,
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'password' => $this->password,
            'date_naissance' =>  $date_naissance,
            'lieu_naissance' => $row['lieu_naissance'],
            'genre' => $row['genre'],
            'user_id' => auth()->user()->id,
            'reserves' => ApprenantController::diff_array($row, ((new ApprenantStoreRequest())->rules())),
        ]);

        $promoReferentiel = PromoReferentiel::where([
            ['promo_id', '=', $this->promoId],
            ['referentiel_id', '=', $this->referentielId]
        ])->first();

        
        // $promoReferentielApprenant = new PromoReferentielApprenant([
            //     "referentiel_id" => $this->referentielId,
            //     "promo_id" => $this->promoId,
            //     "apprenant_id" => $apprenant->id,
            // ]);
            
            $apprenant->save();
            $apprenant->promoReferentiels()->attach($promoReferentiel);

        return [$apprenant];
    }
    public function rules(): array
    {
        return [
            '*.nom' => ['required', 'string', 'max:255'],
            '*.prenom' => ['required', 'string', 'max:255'],
            '*.email' => ['required', 'email', 'max:255', 'unique:apprenants,email'],
            '*.password' => ['sometimes', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',],
            '*.date_naissance' => ['required', 'date'],
            '*.lieu_naissance' => ['required', 'string', 'max:255'],
            '*.telephone' => ['required' , 'regex:/^([0-9\s\-\+\(\)]*)$/' , 'min:9'],
            '*.cni' => ['required' , 'regex:/^([0-9]*)$/' , 'min:17'],
            '*.genre' => ['required', 'in:Masculin,Feminin'],
            '*.photo' => ['nullable'],
            '*.reserves' => ['nullable'],
        ];
    }
}
