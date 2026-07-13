<?php

namespace Tests\Feature;

use App\Models\BorrowingSchedule;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $member;

    private string $adminToken;

    private string $memberToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->member = User::factory()->create(['role' => 'member']);

        $this->adminToken = $this->admin->createToken('admin')->plainTextToken;
        $this->memberToken = $this->member->createToken('member')->plainTextToken;
    }

    public function test_admin_bisa_akses_api_admin(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->getJson('/api/admin/members');

        $response->assertStatus(200);
    }

    public function test_member_dapat_403_saat_akses_api_admin(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->memberToken,
        ])->getJson('/api/admin/members');

        $response->assertStatus(403)
            ->assertJson(['status' => false]);
    }

    public function test_guest_dapat_401_saat_akses_api_admin(): void
    {
        $response = $this->getJson('/api/admin/members');

        $response->assertStatus(401);
    }

    public function test_admin_bisa_melihat_list_inventory_dengan_filter(): void
    {
        Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);
        Inventory::create(['name' => 'Studio A', 'type' => 'workspace', 'status' => 'maintenance']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->getJson('/api/admin/inventories?type=equipment');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Kamera', $response->json('data.0.name'));
    }

    public function test_admin_bisa_membuat_inventory(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->postJson('/api/admin/inventories', [
            'name' => 'Proyektor',
            'type' => 'equipment',
            'status' => 'available',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('inventories', ['name' => 'Proyektor']);
    }

    public function test_create_inventory_validation_error_422(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->postJson('/api/admin/inventories', [
            'name' => '', // kosong
            'type' => 'invalid-type',
            'status' => 'available',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_bisa_menghapus_inventory_tanpa_transaksi(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->deleteJson('/api/admin/inventories/'.$inventory->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('inventories', ['id' => $inventory->id]);
    }

    public function test_delete_inventory_ditolak_409_jika_punya_schedule(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);

        BorrowingSchedule::create([
            'user_id' => $this->member->id,
            'inventory_id' => $inventory->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'booked',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->deleteJson('/api/admin/inventories/'.$inventory->id);

        $response->assertStatus(409);
        $this->assertDatabaseHas('inventories', ['id' => $inventory->id]);
    }

    public function test_admin_members_lookup_hanya_tampilkan_member(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->getJson('/api/admin/members');

        $response->assertStatus(200);
        $data = $response->json('data');

        foreach ($data as $u) {
            $this->assertNotEquals($this->admin->id, $u['id']);
        }
    }

    public function test_admin_bisa_membuat_schedule_tanpa_bentrok(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->postJson('/api/admin/schedules', [
            'user_id' => $this->member->id,
            'inventory_id' => $inventory->id,
            'start_time' => now()->addDay()->format('Y-m-d H:i:s'),
            'end_time' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
            'status' => 'booked',
        ]);

        $response->assertStatus(201);
    }

    public function test_schedule_ditolak_jika_bentrok(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera', 'type' => 'equipment', 'status' => 'available']);

        $start = now()->addDay();
        $end = now()->addDay()->addHours(3);

        BorrowingSchedule::create([
            'user_id' => $this->member->id,
            'inventory_id' => $inventory->id,
            'start_time' => $start,
            'end_time' => $end,
            'status' => 'booked',
        ]);

        // Request overlap (mulai 1 jam setelah start, selesai 1 jam setelah end)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->postJson('/api/admin/schedules', [
            'user_id' => $this->member->id,
            'inventory_id' => $inventory->id,
            'start_time' => $start->copy()->addHour()->format('Y-m-d H:i:s'),
            'end_time' => $end->copy()->addHour()->format('Y-m-d H:i:s'),
            'status' => 'booked',
        ]);

        $response->assertStatus(409);
    }

    public function test_search_inventory_case_insensitive(): void
    {
        Inventory::create(['name' => 'Laptop Editing', 'type' => 'equipment', 'status' => 'available']);

        // Search dengan huruf kapital semua harus tetap menemukan "Laptop Editing"
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->getJson('/api/admin/inventories?search=LAPTOP');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals('Laptop Editing', $response->json('data.0.name'));
    }

    public function test_search_member_case_insensitive(): void
    {
        // member sudah dibuat di setUp()
        $name = $this->member->name;

        // Search dengan case berbeda
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->getJson('/api/admin/members?search='.strtoupper($name));

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    public function test_fk_restrict_schedule_tidak_terhapus_saat_inventory_dihapus_via_api(): void
    {
        $inventory = Inventory::create(['name' => 'Kamera FK Test', 'type' => 'equipment', 'status' => 'available']);

        $schedule = BorrowingSchedule::create([
            'user_id' => $this->member->id,
            'inventory_id' => $inventory->id,
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'booked',
        ]);

        // API harus menolak
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
        ])->deleteJson('/api/admin/inventories/'.$inventory->id);

        $response->assertStatus(409);

        // Schedule harus tetap ada
        $this->assertDatabaseHas('borrowing_schedules', ['id' => $schedule->id]);
        $this->assertDatabaseHas('inventories', ['id' => $inventory->id]);
    }
}
