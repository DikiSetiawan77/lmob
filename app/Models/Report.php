<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'title',
        'description',
        'photos',
    ];

    /**
     * The attributes that should be cast.
     * Ini akan memastikan kolom 'photos' selalu di-handle sebagai array.
     *
     * @var array
     */
    protected $casts = [
        'photos' => 'array',
    ];

    /**
     * Relasi ke User: Setiap laporan dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}