<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\PromoReferentielApprenant;
use App\Models\Promo_Referentiel_Apprenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Promo_Referentiel_ApprenantController
 */
class Promo_Referentiel_ApprenantControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $promoReferentielApprenants = Promo_Referentiel_Apprenant::factory()->count(3)->create();

        $response = $this->get(route('promo_-referentiel_-apprenant.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Promo_Referentiel_ApprenantController::class,
            'store',
            \App\Http\Requests\Promo_Referentiel_ApprenantStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('promo_-referentiel_-apprenant.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(promoReferentielApprenants, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $promoReferentielApprenant = Promo_Referentiel_Apprenant::factory()->create();

        $response = $this->get(route('promo_-referentiel_-apprenant.show', $promoReferentielApprenant));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Promo_Referentiel_ApprenantController::class,
            'update',
            \App\Http\Requests\Promo_Referentiel_ApprenantUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $promoReferentielApprenant = Promo_Referentiel_Apprenant::factory()->create();

        $response = $this->put(route('promo_-referentiel_-apprenant.update', $promoReferentielApprenant));

        $promoReferentielApprenant->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $promoReferentielApprenant = Promo_Referentiel_Apprenant::factory()->create();
        $promoReferentielApprenant = PromoReferentielApprenant::factory()->create();

        $response = $this->delete(route('promo_-referentiel_-apprenant.destroy', $promoReferentielApprenant));

        $response->assertNoContent();

        $this->assertModelMissing($promoReferentielApprenant);
    }
}
