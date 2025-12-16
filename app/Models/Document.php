<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'original_name',
        'status',
        'verified_by',
        'verified_at',
        'rejection_note',
    ];

    /**
     * The attributes that should be cast.
     * Ini akan memastikan kolom tanggal selalu di-handle sebagai objek Carbon.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Relasi ke User: Setiap dokumen dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}