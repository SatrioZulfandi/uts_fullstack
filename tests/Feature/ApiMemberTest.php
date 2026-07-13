<?php

namespace Tests\Feature;

use App\Models\BorrowingSchedule;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiMemberTest extends TestCase
{
    use RefreshDatabase;

    private User $member1;

    private User $member2;

    private string $token1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member1 = User::factory()->create(['role' => 'member']);
        $this->member2 = User::factory()->create(['role' => 'member']);

        $this->token1 = $this->member1->createToken('member1')->plainTextToken;
    }

    public function test_member_bisa_melihat_inventory_available(): void
    {
        Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);
        Inventory::create(['name' => 'Tripod', 'type' => 'equipment', 'status' => 'maintenance']);
        Inventory::create(['name' => 'Lampu', 'type' => 'equipment', 'status' => 'borrowed']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token1,
        ])->getJson('/api/inventories');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Kamera', $data[0]['name']);
    }

    public function test_member_hanya_melihat_schedule_miliknya(): void
    {
        $inv1 = Inventory::create(['name' => 'A', 'type' => 'equipment']);
        $inv2 = Inventory::create(['name' => 'B', 'type' => 'equipment']);

        BorrowingSchedule::create([
            'user_id' => $this->member1->id,
            'inventory_id' => $inv1->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'booked',
        ]);

        BorrowingSchedule::create([
            'user_id' => $this->member2->id,
            'inventory_id' => $inv2->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'booked',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token1,
        ])->getJson('/api/my-schedules');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($inv1->id, $response->json('data.0.inventory_id'));
    }

    public function test_detail_schedule_milik_orang_lain_ditolak_403_atau_404(): void
    {
        $inv2 = Inventory::create(['name' => 'B', 'type' => 'equipment']);

        $schedule = BorrowingSchedule::create([
            'user_id' => $this->member2->id,
            'inventory_id' => $inv2->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'booked',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token1,
        ])->getJson('/api/my-schedules/'.$schedule->id);

        $response->assertStatus(403);
    }

    public function test_check_in_berhasil(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);

        $schedule = BorrowingSchedule::create([
            'user_id' => $this->member1->id,
            'inventory_id' => $inventory->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'booked',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token1,
        ])->postJson('/api/check-in', [
            'schedule_id' => $schedule->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('borrowing_schedules', [
            'id' => $schedule->id,
            'status' => 'checked_in',
        ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'status' => 'borrowed',
        ]);
    }

    public function test_check_in_ulang_ditolak_409(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'borrowed']);

        $schedule = BorrowingSchedule::create([
            'user_id' => $this->member1->id,
            'inventory_id' => $inventory->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'checked_in',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token1,
        ])->postJson('/api/check-in', [
            'schedule_id' => $schedule->id,
        ]);

        $response->assertStatus(409);
    }

    public function test_check_in_tanpa_schedule_id_422(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->token1,
        ])->postJson('/api/check-in', []);

        $response->assertStatus(422);
    }
}
