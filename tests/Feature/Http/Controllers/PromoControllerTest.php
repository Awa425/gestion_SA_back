<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Owner;
use App\Models\Promo;
use App\Models\Uid;
use App\Models\User;
use Carbon\Carbon;
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
        $date_debut = $this->faker->date();
        $date_fin_prevue = $this->faker->date();
        $date_fin_reel = $this->faker->date();
        $is_active = $this->faker->boolean;
        $user = User::factory()->create();
        $owner = Owner::factory()->create();
        $uid = Uid::factory()->create();

        $response = $this->post(route('promo.store'), [
            'libelle' => $libelle,
            'date_debut' => $date_debut,
            'date_fin_prevue' => $date_fin_prevue,
            'date_fin_reel' => $date_fin_reel,
            'is_active' => $is_active,
            'user_id' => $user->id,
            'owner_id' => $owner->id,
            'uid' => $uid->id,
        ]);

        $promos = Promo::query()
            ->where('libelle', $libelle)
            ->where('date_debut', $date_debut)
            ->where('date_fin_prevue', $date_fin_prevue)
            ->where('date_fin_reel', $date_fin_reel)
            ->where('is_active', $is_active)
            ->where('user_id', $user->id)
            ->where('owner_id', $owner->id)
            ->where('uid', $uid->id)
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
        $date_debut = $this->faker->date();
        $date_fin_prevue = $this->faker->date();
        $date_fin_reel = $this->faker->date();
        $is_active = $this->faker->boolean;
        $user = User::factory()->create();
        $owner = Owner::factory()->create();
        $uid = Uid::factory()->create();

        $response = $this->put(route('promo.update', $promo), [
            'libelle' => $libelle,
            'date_debut' => $date_debut,
            'date_fin_prevue' => $date_fin_prevue,
            'date_fin_reel' => $date_fin_reel,
            'is_active' => $is_active,
            'user_id' => $user->id,
            'owner_id' => $owner->id,
            'uid' => $uid->id,
        ]);

        $promo->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($libelle, $promo->libelle);
        $this->assertEquals(Carbon::parse($date_debut), $promo->date_debut);
        $this->assertEquals(Carbon::parse($date_fin_prevue), $promo->date_fin_prevue);
        $this->assertEquals(Carbon::parse($date_fin_reel), $promo->date_fin_reel);
        $this->assertEquals($is_active, $promo->is_active);
        $this->assertEquals($user->id, $promo->user_id);
        $this->assertEquals($owner->id, $promo->owner_id);
        $this->assertEquals($uid->id, $promo->uid);
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
