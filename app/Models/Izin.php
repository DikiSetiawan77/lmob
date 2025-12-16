<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'izin_type',
        'date',
        'allowed_time',
        'note',
        // Pastikan kolom-kolom ini ada:
        'status',
        'approved_by',
        'approved_at',
        'rejection_note',
    ];

    /**
     * Relasi ke User: Setiap pengajuan izin dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        // Relasi ke model User melalui foreign key 'approved_by'
        return $this->belongsTo(User::class, 'approved_by');
    }
}