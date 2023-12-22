<?php

namespace App\Http\Requests\import;

use Carbon\Carbon;
use App\Models\Promo;
use App\Models\Apprenant;
use App\Models\Referentiel;
use App\Models\PromoReferentiel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use App\Http\Requests\ApprenantStoreRequest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Http\Controllers\ApprenantController;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class ApprenantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsEmptyRows, ToCollection
{
    use Importable, SkipsErrors, SkipsFailures;

    private $promoId;
    private $referentielId;
    private $password;
    private $promo;
    private $referentiel;
    private $referentiel_libelle;
    private $promo_libelle;
    private $apprenants = [];
    private $apprenantController;

    public function __construct()
    {
        $this->promoId = request()->promo_id;
        $this->referentielId = request()->referentiel_id;
        $this->password = bcrypt('Passer');
        $this->promo = Promo::find($this->promoId);
        $this->referentiel = Referentiel::find($this->referentielId);
        $this->promo_libelle = $this->promo->libelle;
        $this->referentiel_libelle = $this->referentiel->libelle;
        $this->apprenantController = new ApprenantController();
    }


    public function model(array $row)
    {
        // $date_naissance = Carbon::createFromFormat('Y-m-d', $row['date_naissance'])->toDateString();
        // $matricule = ApprenantController::generate_matricule($this->promo_libelle, $this->referentiel_libelle);
        $matricule = $this->apprenantController->generate_matricule($this->promo_libelle, $this->referentiel_libelle);

        $date_naissance = Carbon::parse($row['date_naissance'])->toDateString();
        // dd($row);
        $apprenant = new Apprenant([
            'matricule' => $matricule,
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
            'password' => $this->password,
            'date_naissance' => $date_naissance,
            'lieu_naissance' => $row['lieu_naissance'],
            'genre' => $row['genre'],
            'user_id' => auth()->user()->id,
            'reserves' => ApprenantController::diff_array($row, ((new ApprenantStoreRequest())->rules())),
        ]);

        $promoReferentiel = PromoReferentiel::where([
            ['promo_id', '=', $this->promoId],
            ['referentiel_id', '=', $this->referentielId]
        ])->first();
        // if ($this->apprenantExist($apprenant->email)) {
        //     $this->apprenantExist($apprenant->email)->promoReferentiels()->attach($promoReferentiel);
        // }
        $apprenant->save();
        $apprenant->promoReferentiels()->attach($promoReferentiel);

        $this->apprenants[] = $apprenant;

        return [$apprenant];
    }


    public function getApprenants()
    {
        return $this->apprenants;
    }
    public function apprenantExist($email){
        return Apprenant::where('email', $email)->first();
    }

    public function rules(): array
    {
        // dd(request());
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'sometimes|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'sometimes|required|nullable',
            'telephone' => 'required|nullable',
            'cni' => 'sometimes|required|numeric|nullable',
            'genre' => 'required|string|nullable',
            'photo' => 'sometimes|required|mimes:png,jpg,jpeg,gif,webp',

        ];
    }

    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $collection)
    {
    }
}
