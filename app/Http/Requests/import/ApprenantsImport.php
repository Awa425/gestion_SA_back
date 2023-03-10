<?php

namespace App\Http\Requests\import;

use App\Models\Apprenant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\PromoReferentielApprenant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ApprenantsImport implements ToModel, WithHeadingRow
{
    protected $promo_id;
    protected $referentiel_id;
    // permet d'importer les id de referentiel et promo renseigner dans l'url
    public function __construct($promo_id, $referentiel_id)
    {
        $this->promo_id = $promo_id;
        $this->referentiel_id = $referentiel_id;
    }
    public function model(array $row)
    {
        $u = auth()->user()->id;
        $apprenant = new Apprenant([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'password' => $row['password'],
            'date_naissance' => $row['date_naissance'],
            'lieu_naissance' => $row['lieu_naissance'],
            'user_id' => $u,
        ]);
        $apprenant->save();

        $promoReferentielApprenant = new PromoReferentielApprenant([
            "promo_id" => $this->promo_id,
            "referentiel_id" => $this->referentiel_id,
            "apprenant_id" => $apprenant->id,
        ]);

        return [$apprenant, $promoReferentielApprenant];
    }


    public function rules(): array
    {
        return [
            '*.nom' => ['required', 'string', 'max:255'],
            '*.prenom' => ['required', 'string', 'max:255'],
            '*.email' => ['required', 'email', 'max:255', 'unique:apprenants,email'],
            '*.telephone' => ['required'],
            '*.password' => ['required', 'string', 'max:255'],
            '*.date_naissance' => ['required', 'date'],
            '*.lieu_naissance' => ['required', 'string', 'max:255'],
            
        ];
    }
}
