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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ApprenantsImport implements ToModel, WithHeadingRow
{

    

    public function generate_matricule($promo_id, $referentiel_id)
    {
        // Define the cache keys for promo and referentiel
        $promo_cache_key = 'promo_' . $promo_id;
        $referentiel_cache_key = 'referentiel_' . $referentiel_id;
    
        // Check if the cache exists for promo and referentiel
        $promo = Cache::get($promo_cache_key);
        $referentiel = Cache::get($referentiel_cache_key);
    
        // If the cache doesn't exist, execute the queries and store the results in the cache
        if (!$promo) {
            $promo = Promo::where('id', '=', $promo_id)->select('libelle')->first();
            Cache::put($promo_cache_key, $promo, $seconds = 20); // Cache for 20 seconds
        }
        if (!$referentiel) {
            $referentiel = Referentiel::where('id', '=', $referentiel_id)->select('libelle')->first();
            Cache::put($referentiel_cache_key, $referentiel, $seconds = 20); // Cache for 20 seconds
        }
    
        $promo_tabs = explode(' ', $promo['libelle']);
        $referentiel_tabs = explode(' ', $referentiel['libelle']);
        $promo_prefix = '';
        $referentiel_prefix = '';
    
        foreach ($promo_tabs as $promo_tab) {
            $promo_prefix .= strtoupper(substr($promo_tab, 0, 1));
        }
        foreach ($referentiel_tabs as $referentiel_tab) {
            $referentiel_prefix .= strtoupper(substr($referentiel_tab, 0, 1));
        }
    
        $string= Str::random(5);
        $date = date('Ymd');
        $matricule = $promo_prefix . '_' . $referentiel_prefix . '_' . $string . '_' . $date;
        return $matricule;

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
        
        $mat= $this->generate_matricule(request()->promo_id,request()->referentiel_id);
        $u = auth()->user()->id;

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
