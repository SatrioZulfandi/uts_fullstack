<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingSchedule extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'borrowing_schedules';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'inventory_id',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * Casting atribut ke tipe data yang sesuai.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    /**
     * Relasi: Jadwal peminjaman dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Jadwal peminjaman terkait dengan satu inventaris.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
