<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Referentiel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ReferentielController
 */
class ReferentielControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $referentiels = Referentiel::factory()->count(3)->create();

        $response = $this->get(route('referentiel.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ReferentielController::class,
            'store',
            \App\Http\Requests\ReferentielStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $libelle = $this->faker->word;
        $description = $this->faker->text;
        $is_active = $this->faker->boolean;

        $response = $this->post(route('referentiel.store'), [
            'libelle' => $libelle,
            'description' => $description,
            'is_active' => $is_active,
        ]);

        $referentiels = Referentiel::query()
            ->where('libelle', $libelle)
            ->where('description', $description)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $referentiels);
        $referentiel = $referentiels->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $referentiel = Referentiel::factory()->create();

        $response = $this->get(route('referentiel.show', $referentiel));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ReferentielController::class,
            'update',
            \App\Http\Requests\ReferentielUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $referentiel = Referentiel::factory()->create();
        $libelle = $this->faker->word;
        $description = $this->faker->text;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('referentiel.update', $referentiel), [
            'libelle' => $libelle,
            'description' => $description,
            'is_active' => $is_active,
        ]);

        $referentiel->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($libelle, $referentiel->libelle);
        $this->assertEquals($description, $referentiel->description);
        $this->assertEquals($is_active, $referentiel->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $referentiel = Referentiel::factory()->create();

        $response = $this->delete(route('referentiel.destroy', $referentiel));

        $response->assertNoContent();

        $this->assertModelMissing($referentiel);
    }
}
