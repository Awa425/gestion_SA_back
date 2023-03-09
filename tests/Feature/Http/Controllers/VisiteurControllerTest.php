<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Visiteur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\VisiteurController
 */
class VisiteurControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $visiteurs = Visiteur::factory()->count(3)->create();

        $response = $this->get(route('visiteur.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VisiteurController::class,
            'store',
            \App\Http\Requests\VisiteurStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $nom = $this->faker->word;
        $prenom = $this->faker->word;
        $INE = $this->faker->numberBetween(-100000, 100000);
        $motif = $this->faker->word;

        $response = $this->post(route('visiteur.store'), [
            'nom' => $nom,
            'prenom' => $prenom,
            'INE' => $INE,
            'motif' => $motif,
        ]);

        $visiteurs = Visiteur::query()
            ->where('nom', $nom)
            ->where('prenom', $prenom)
            ->where('INE', $INE)
            ->where('motif', $motif)
            ->get();
        $this->assertCount(1, $visiteurs);
        $visiteur = $visiteurs->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $visiteur = Visiteur::factory()->create();

        $response = $this->get(route('visiteur.show', $visiteur));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\VisiteurController::class,
            'update',
            \App\Http\Requests\VisiteurUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $visiteur = Visiteur::factory()->create();
        $nom = $this->faker->word;
        $prenom = $this->faker->word;
        $INE = $this->faker->numberBetween(-100000, 100000);
        $motif = $this->faker->word;

        $response = $this->put(route('visiteur.update', $visiteur), [
            'nom' => $nom,
            'prenom' => $prenom,
            'INE' => $INE,
            'motif' => $motif,
        ]);

        $visiteur->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($nom, $visiteur->nom);
        $this->assertEquals($prenom, $visiteur->prenom);
        $this->assertEquals($INE, $visiteur->INE);
        $this->assertEquals($motif, $visiteur->motif);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $visiteur = Visiteur::factory()->create();

        $response = $this->delete(route('visiteur.destroy', $visiteur));

        $response->assertNoContent();

        $this->assertModelMissing($visiteur);
    }
}
