<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_berhasil_sebagai_admin(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login berhasil.',
            ])
            ->assertJsonMissing(['password' => 'password']);

        $this->assertArrayHasKey('token', $response->json('data'));
        $this->assertEquals('admin', $response->json('data.user.role'));
    }

    public function test_login_berhasil_sebagai_member(): void
    {
        $member = User::factory()->create([
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'role' => 'member',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'member@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);

        $this->assertArrayHasKey('token', $response->json('data'));
    }

    public function test_login_gagal_karena_credential_salah(): void
    {
        User::factory()->create([
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'user@test.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Email atau password salah.',
            ]);
    }

    public function test_login_validation_error(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response->assertStatus(422);
    }

    public function test_me_berhasil_dengan_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
            ])
            ->assertJsonMissing(['password'])
            ->assertJsonMissing(['remember_token']);
    }

    public function test_me_tanpa_token_mendapat_401(): void
    {
        $response = $this->getJson('/api/me');

        $response->assertStatus(401);
    }

    public function test_logout_berhasil_dan_token_revoked(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        // Token tidak bisa dipakai lagi (paksa request baru tanpa instance tersimpan)
        $this->app->get('auth')->forgetGuards();

        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response2->assertStatus(401);
    }
}
