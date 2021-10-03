<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeveloperTest extends TestCase
{
    use RefreshDatabase;

    const ADMIN_EMAIL = 'admin@crud.com';
    const ADMIN_PASSWORD = 'admin';

    /**
     * test_only_logged_in_user_can_see_all_developers.
     * @test
     * @return void
     */
    public function only_logged_in_user_can_see_all_developers()
    {
        $response = $this->json('get', 'api/developers');
        $response->assertStatus(401);

        // usuário autenticado
        Sanctum::actingAs(User::factory()->create());
        $response = $this->json('get', 'api/developers');
        $response->assertStatus(200);
    }

    /**
     * only_logged_in_user_can_create_a_developer.
     * @test
     * @return void
     */
    public function only_logged_in_user_can_create_a_developer()
    {
        $response = $this->json('post', 'api/developers');
        $response->assertStatus(401);

        $payload = [
            'name'      => 'Developer Test',
            'gender'    => 'male',
            'age'       => '46',
            'hobby'     => 'Programing',
            'birthday'  => '1975-03-30',
        ];

        // usuário autenticado
        Sanctum::actingAs(User::factory()->create());
        $response = $this->json('post', 'api/developers', $payload);
        $response->assertStatus(201);
        $response->assertJson([
            'name'      => 'Developer Test',
            'gender'    => 'male',
            'age'       => '46',
            'hobby'     => 'Programing',
            'birthday' => '1975-03-30T00:00:00.000000Z'
        ]);
    }

    /**
     * only_logged_in_user_can_update_a_developer.
     * @test
     * @return void
     */
    public function only_logged_in_user_can_update_a_developer()
    {
        $response = $this->json('put', 'api/developers/1', ['name' => 'Adriano alterado']);
        $response->assertStatus(401);

        // usuário autenticado
        Sanctum::actingAs(User::factory()->create());
        Developer::factory()->create(['id' => 1, 'name' => 'Adriano']);

        $this->putJson('api/developers/1', ['name' => 'Adriano alterado'])
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'Adriano alterado']);

    }

    /**
     * only_logged_in_user_can_delete_a_developer.
     * @test
     * @return void
     */
    public function only_logged_in_user_can_delete_a_developer()
    {
        $response = $this->json('delete', 'api/developers/1');
        $response->assertStatus(401);

        // usuário autenticado
        Sanctum::actingAs(User::factory()->create());
        Developer::factory()->create(['id' => 1, 'name' => 'Adriano']);

        $response = $this->deleteJson('api/developers/1');
        $response->assertStatus(204);
        $this->assertDatabaseMissing('developers',['id'=> 1]);
    }
}
