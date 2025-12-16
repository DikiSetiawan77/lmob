<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinasLuar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * HANYA ADA SATU DEKLARASI $fillable DI SINI.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Kolom-kolom asli
        'user_id',
        'tipe',
        'lokasi_nama',
        'start_date',
        'end_date',
        'note',
        // Kolom-kolom untuk approval
        'status',
        'approved_by',
        'approved_at',
        'rejection_note',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi ke User.
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