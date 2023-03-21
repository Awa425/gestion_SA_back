<?php

namespace App\Http\Requests\import;

use App\Models\Promo;
use App\Models\Apprenant;
use App\Models\Referentiel;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\PromoReferentielApprenant;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApprenantController;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ApprenantsImport implements ToModel, WithHeadingRow
{
    private $promoId;
    private $referentielId;
    private $password;
    private $promo;
    private $referentiel;

    public function __construct()
    {
        $this->promoId = request()->promo_id;
        $this->referentielId = request()->referentiel_id;
        $this->password = bcrypt('Passer');
        $this->promo = Promo::find($this->promoId);
        $this->referentiel = Referentiel::find($this->referentielId);
    }

    public function model(array $row)
    {

        $prefix = Str::upper(Str::substr($this->promo->libelle, 0, 2)) . Str::upper($this->referentiel->id);

        $promoIdentifier = $this->promoId;
        $referentielIdentifier = $this->referentielId;
        $date = now()->format('YmdHisu');
        $matricule = "{$prefix}_{$promoIdentifier}_{$referentielIdentifier}_{$date}";
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
        ]);

        $apprenant->save();

        $promoReferentielApprenant = new PromoReferentielApprenant([
            "referentiel_id" => $this->referentielId,
            "promo_id" => $this->promoId,
            "apprenant_id" => $apprenant->id,
        ]);

        return [$promoReferentielApprenant];
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
