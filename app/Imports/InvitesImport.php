<?php

namespace App\Imports;

use App\Models\PresenceEvent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InvitesImport implements ToModel,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    private $evenementId;

    public function __construct(){
        $this->evenementId= request()->evenement_id;
    }

    public function model(array $row)
    {
        
       $presenceExiste= PresenceEvent::where(["evenement_id"=>$this->evenementId,"email"
                                    =>$row["email"],"cni"=>$row["cni"]])->first();
        if (!$presenceExiste) {
           return new PresenceEvent([
               "evenement_id"=>$this->evenementId,
               "nom"=>$row["nom"],
               "prenom"=>$row["prenom"],
               "telephone"=>$row["telephone"],
               "email"=>$row["email"],
               "genre"=>$row["genre"],
               "cni"=>$row["cni"],
           ]);
        }
    }
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => 'required|nullable',
            'cni' => 'sometimes|required|numeric|nullable',
            'genre' => 'required|string|nullable',
        ];
    }
}
