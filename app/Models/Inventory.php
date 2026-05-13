<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model.
     */
    protected $table = 'inventories';

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'status',
        'description',
    ];

    /**
     * Relasi: Satu inventaris dapat memiliki banyak jadwal peminjaman.
     */
    public function borrowingSchedules(): HasMany
    {
        return $this->hasMany(BorrowingSchedule::class);
    }
}
