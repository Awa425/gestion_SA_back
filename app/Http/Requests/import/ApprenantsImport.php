<?php

namespace App\Http\Requests\import;

use App\Models\Promo;
use App\Models\Apprenant;
use App\Models\Referentiel;




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
    // public function generate_matricule($promo_li)
    // {
    //     $promo_tabs = explode(' ', $this->promo_libelle);
    //     $referentiel_tabs = explode(' ', $this->referentiel_libelle);
    //     $promo_prefix = '';
    //     $referentiel_prefix = '';

    //     foreach ($promo_tabs as $promo_tab) {
    //         $promo_prefix .= strtoupper(substr($promo_tab, 0, 1));
    //     }
    //     foreach ($referentiel_tabs as $referentiel_tab) {
    //         $referentiel_prefix .= strtoupper(substr($referentiel_tab, 0, 1));
    //     }
    //     $date = date('YmdHis') . number_format(microtime(true), 3, '', '');
    //     $matricule = $promo_prefix . '_' . $referentiel_prefix . '_'  . $date;
    //     return $matricule;
    // }
    public function model(array $row)
    {

        
      
        $matricule=ApprenantController::generate_matricule($this->promo_libelle,$this->referentiel_libelle);


        $apprenant = new Apprenant([
            'matricule' => $matricule,
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'password' => $this->password,
            'date_naissance' => $row['date_naissance'],
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
            '*.telephone' => ['required' , 'regex:/^([0-9\s\-\+\(\)]*)$/' , 'min:10'],
            '*.password' => ['required', 'string', 'max:255'],
            '*.date_naissance' => ['required', 'date'],
            '*.lieu_naissance' => ['required', 'string', 'max:255'],
            '*.genre' => ['required', 'in:M,F'],
        ];
    }
}
