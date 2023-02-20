<?php

namespace Tests\Feature;

use App\Models\Community;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommunityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function can_fetch_all_communities() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $communities = Community::factory(3)->create();
        $response = $this->getJson('/api/communities');
        $response->assertOk();
        $response->assertJson([
            'data' => [
                [
                    'id' => $communities[0]->id,
                    'name' => $communities[0]->name,
                    'description' => $communities[0]->description,
                    'rules' => $communities[0]->rules,
                ],
                [
                    'id' => $communities[1]->id,
                    'name' => $communities[1]->name,
                    'description' => $communities[1]->description,
                    'rules' => $communities[1]->rules,
                ],
                [
                    'id' => $communities[2]->id,
                    'name' => $communities[2]->name,
                    'description' => $communities[2]->description,
                    'rules' => $communities[2]->rules,
                ],
            ]
        ]);
    }
    /**
     * @test
     */
    public function can_fetch_single_community() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $community = Community::factory()->create();
        $response = $this->getJson("/api/communities/$community->id");
        $response->assertOk();
        $response->assertJson([
            'data' =>  [
                'id' => $community->id,
                'name' => $community->name,
                'description' => $community->description,
                'rules' => $community->rules,
            ]
        ]);
    }

    /**
     * @test
     */
    public function name_field_is_required() {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/communities", [
            'description' => 'Lorem ipsum',
            'rules' => '1.2.3.',
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The name field is required.'
        ]);
    }

    /**
     * @test
     */
    public function can_create_community() {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/communities', [
            'name' => 'Prueba1',
            'description' => 'Lorem ipsum dolor est',
            'rules' => '1.2.3.4.5.',
        ]);
        $response->assertCreated();
        $response->assertJson([
            'data' => [
                'name' => 'Prueba1',
                'description' => 'Lorem ipsum dolor est',
                'rules' => '1.2.3.4.5.',
            ]
        ]);
    }

    /**
     * @test
     */
    public function guests_cannot_create_community() {
        $response = $this->postJson('/api/communities', [
            'name' => 'Prueba1',
            'description' => 'Lorem ipsum dolor est',
            'rules' => '1.2.3.4.5.',
        ]);
        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function can_update_community() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $community = Community::factory()->create([
            'name' => 'Test update 1',
            'description' => 'Lorem',
            'rules' => '1234',
        ]);

        $response = $this->putJson("/api/communities/$community->id", [
            'name' => 'Test update 1.2',
        ]);

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'name' => 'Test update 1.2',
                'description' => 'Lorem',
                'rules' => '1234',
            ]
        ]);
    }

    /**
     * @test
     */
    public function can_delete_community() {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $community = Community::factory()->create();

        $response = $this->deleteJson("/api/communities/$community->id");

        $response->assertNoContent();

        $this->withExceptionHandling();

        $response = $this->getJson("/api/communities/$community->id");
        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_return_json_api_error_object_when_not_found() {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/communities/12345');
        $response->assertNotFound();
        $response->assertJson([
            'error' => 'not found',
        ]);
    }
}
