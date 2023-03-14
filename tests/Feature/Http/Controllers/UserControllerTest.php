<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('user.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'store',
            \App\Http\Requests\UserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $name = $this->faker->name;
        $prenom = $this->faker->word;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $isactive = $this->faker->boolean;
        $isfirstlyconnected = $this->faker->boolean;
        $role_id = $this->faker->numberBetween(-100000, 100000);

        $response = $this->post(route('user.store'), [
            'name' => $name,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'isactive' => $isactive,
            'isfirstlyconnected' => $isfirstlyconnected,
            'role_id' => $role_id,
        ]);

        $users = User::query()
            ->where('name', $name)
            ->where('prenom', $prenom)
            ->where('email', $email)
            ->where('password', $password)
            ->where('isactive', $isactive)
            ->where('isfirstlyconnected', $isfirstlyconnected)
            ->where('role_id', $role_id)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.show', $user));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'update',
            \App\Http\Requests\UserUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $user = User::factory()->create();
        $name = $this->faker->name;
        $prenom = $this->faker->word;
        $email = $this->faker->safeEmail;
        $password = $this->faker->password;
        $isactive = $this->faker->boolean;
        $isfirstlyconnected = $this->faker->boolean;
        $role_id = $this->faker->numberBetween(-100000, 100000);

        $response = $this->put(route('user.update', $user), [
            'name' => $name,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'isactive' => $isactive,
            'isfirstlyconnected' => $isfirstlyconnected,
            'role_id' => $role_id,
        ]);

        $user->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($prenom, $user->prenom);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($isactive, $user->isactive);
        $this->assertEquals($isfirstlyconnected, $user->isfirstlyconnected);
        $this->assertEquals($role_id, $user->role_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }
}
