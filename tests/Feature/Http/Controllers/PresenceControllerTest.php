<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Presence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PresenceController
 */
class PresenceControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $presences = Presence::factory()->count(3)->create();

        $response = $this->get(route('presence.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PresenceController::class,
            'store',
            \App\Http\Requests\PresenceStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $response = $this->post(route('presence.store'));

        $response->assertCreated();
        $response->assertJsonStructure([]);

        $this->assertDatabaseHas(presences, [ /* ... */ ]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $presence = Presence::factory()->create();

        $response = $this->get(route('presence.show', $presence));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PresenceController::class,
            'update',
            \App\Http\Requests\PresenceUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $presence = Presence::factory()->create();

        $response = $this->put(route('presence.update', $presence));

        $presence->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $presence = Presence::factory()->create();

        $response = $this->delete(route('presence.destroy', $presence));

        $response->assertNoContent();

        $this->assertModelMissing($presence);
    }
}
