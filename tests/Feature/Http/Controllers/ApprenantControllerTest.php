<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Apprenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ApprenantController
 */
class ApprenantControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $apprenants = Apprenant::factory()->count(3)->create();

        $response = $this->get(route('apprenant.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ApprenantController::class,
            'store',
            \App\Http\Requests\ApprenantStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $nom = $this->faker->word;
        $prenom = $this->faker->word;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $date_naissance = $this->faker->date();
        $lieu_naissance = $this->faker->word;
        $is_active = $this->faker->boolean;

        $response = $this->post(route('apprenant.store'), [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'date_naissance' => $date_naissance,
            'lieu_naissance' => $lieu_naissance,
            'is_active' => $is_active,
        ]);

        $apprenants = Apprenant::query()
            ->where('nom', $nom)
            ->where('prenom', $prenom)
            ->where('email', $email)
            ->where('password', $password)
            ->where('date_naissance', $date_naissance)
            ->where('lieu_naissance', $lieu_naissance)
            ->where('is_active', $is_active)
            ->get();
        $this->assertCount(1, $apprenants);
        $apprenant = $apprenants->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $apprenant = Apprenant::factory()->create();

        $response = $this->get(route('apprenant.show', $apprenant));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ApprenantController::class,
            'update',
            \App\Http\Requests\ApprenantUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $apprenant = Apprenant::factory()->create();
        $nom = $this->faker->word;
        $prenom = $this->faker->word;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $date_naissance = $this->faker->date();
        $lieu_naissance = $this->faker->word;
        $is_active = $this->faker->boolean;

        $response = $this->put(route('apprenant.update', $apprenant), [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'date_naissance' => $date_naissance,
            'lieu_naissance' => $lieu_naissance,
            'is_active' => $is_active,
        ]);

        $apprenant->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($nom, $apprenant->nom);
        $this->assertEquals($prenom, $apprenant->prenom);
        $this->assertEquals($email, $apprenant->email);
        $this->assertEquals($password, $apprenant->password);
        $this->assertEquals(Carbon::parse($date_naissance), $apprenant->date_naissance);
        $this->assertEquals($lieu_naissance, $apprenant->lieu_naissance);
        $this->assertEquals($is_active, $apprenant->is_active);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $apprenant = Apprenant::factory()->create();

        $response = $this->delete(route('apprenant.destroy', $apprenant));

        $response->assertNoContent();

        $this->assertModelMissing($apprenant);
    }
}
