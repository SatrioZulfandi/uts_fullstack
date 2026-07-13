<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * GET /api/admin/inventories
     * Mengambil daftar inventaris dengan pagination dan filter.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Inventory::query();

        if ($request->has('search') && ! empty($request->search)) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->has('type') && ! empty($request->type)) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && ! empty($request->status)) {
            $query->where('status', $request->status);
        }

        $inventories = $query->orderBy('created_at', 'desc')->paginate($request->input('per_page', 10));

        return response()->json([
            'status' => true,
            'message' => 'Daftar inventaris berhasil diambil.',
            'data' => $inventories->items(),
            'meta' => [
                'current_page' => $inventories->currentPage(),
                'last_page' => $inventories->lastPage(),
                'per_page' => $inventories->perPage(),
                'total' => $inventories->total(),
            ],
            'links' => [
                'first' => $inventories->url(1),
                'last' => $inventories->url($inventories->lastPage()),
                'prev' => $inventories->previousPageUrl(),
                'next' => $inventories->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * POST /api/admin/inventories
     * Membuat inventaris baru.
     */
    public function store(InventoryRequest $request): JsonResponse
    {
        $inventory = Inventory::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Inventaris berhasil ditambahkan.',
            'data' => $inventory,
        ], 201);
    }

    /**
     * DELETE /api/admin/inventories/{inventory}
     * Menghapus inventaris (jika aman).
     */
    public function destroy($id): JsonResponse
    {
        $inventory = Inventory::find($id);

        if (! $inventory) {
            return response()->json([
                'status' => false,
                'message' => 'Inventaris tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if ($inventory->borrowingSchedules()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Inventaris tidak dapat dihapus karena masih digunakan dalam transaksi.',
                'data' => null,
            ], 409);
        }

        $inventory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Inventaris berhasil dihapus.',
            'data' => null,
        ], 200);
    }
}
