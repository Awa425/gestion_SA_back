<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Resources\ApprenantCollection;
use App\Http\Resources\ApprenantResource;
use App\Http\Requests\ValidateDataApp;
use App\Http\Requests\ApprenantsImport;

use App\Models\Apprenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApprenantController extends Controller
{
    public function index(Request $request): ApprenantCollection
    {
        $apprenants = Apprenant::all();

        return new ApprenantCollection($apprenants);
    }

    public function store(ApprenantStoreRequest $request): ApprenantResource
    {
        $apprenant = Apprenant::create($request->validated());

        return new ApprenantResource($apprenant);
    }
    public function storeExcel(Request $request)
    {
        if (!$request->hasFile('excel_file')) {
            return response()->json([
                'message' => 'Veuillez sélectionner un fichier Excel à importer.'] , 422
            );
        }
        $file = $request->file('excel_file');

        $data = Excel::import(new ApprenantsImport, $file);

        $apprenants = Apprenant::create($data->toArray());

        return new ApprenantResource($apprenants);
    }


    public function show(Request $request, Apprenant $apprenant): ApprenantResource
    {
        return new ApprenantResource($apprenant);
    }

    public function update(ApprenantUpdateRequest $request, Apprenant $apprenant): ApprenantResource
    {
        $apprenant->update($request->validated());

        return new ApprenantResource($apprenant);
    }

    public function destroy(Request $request, Apprenant $apprenant): Response
    {
        $apprenant->delete();

        return response()->noContent();
    }
}
