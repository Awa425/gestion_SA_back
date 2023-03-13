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
    public function model(array $row)
    {
        $u = auth()->user()->id;
        $apprenant = new Apprenant([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'password' => bcrypt('Passer'),
            'date_naissance' => $row['date_naissance'],
            'lieu_naissance' => $row['lieu_naissance'],
            'user_id' => $u,
        ]);
        $apprenant->save();

        $promoReferentielApprenant = new PromoReferentielApprenant([
            "referentiel_id" => request()->referentiel_id,
            "promo_id" => request()->promo_id,
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
            '*.telephone' => ['required'],
            '*.password' => ['required', 'string', 'max:255'],
            '*.date_naissance' => ['required', 'date'],
            '*.lieu_naissance' => ['required', 'string', 'max:255'],
            
        ];
    }
}
