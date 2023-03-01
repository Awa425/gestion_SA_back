<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Promo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PromoController
 */
class PromoControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $promos = Promo::factory()->count(3)->create();

        $response = $this->get(route('promo.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PromoController::class,
            'store',
            \App\Http\Requests\PromoStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $libelle = $this->faker->word;
        $annee = $this->faker->word;
        $is_active = $this->faker->boolean;

        $response = $this->post(route('promo.store'), [
            'libelle' => $libelle,
            'annee' => $annee,
            'is_active' => $is_active,
        ]);

        $promos = Promo::query()
            ->where('libelle', $libelle)
            ->where('annee', $annee)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $promos);
        $promo = $promos->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $promo = Promo::factory()->create();

        $response = $this->get(route('promo.show', $promo));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PromoController::class,
            'update',
            \App\Http\Requests\PromoUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $promo = Promo::factory()->create();
        $libelle = $this->faker->word;
        $annee = $this->faker->word;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('promo.update', $promo), [
            'libelle' => $libelle,
            'annee' => $annee,
            'is_active' => $is_active,
        ]);

        $promo->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($libelle, $promo->libelle);
        $this->assertEquals($annee, $promo->annee);
        $this->assertEquals($is_active, $promo->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $promo = Promo::factory()->create();

        $response = $this->delete(route('promo.destroy', $promo));

        $response->assertNoContent();

        $this->assertModelMissing($promo);
    }
}
