<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    public const INACTIVE = 'inactive';
    public const PENDING = 'pending';
    public const ACTIVE = 'active';
    public const REJECTED = 'rejected';

    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nis',
        'name',
        'gender',
        'religion',
        'class_name',
        'field_of_study',
        'study_program',
        'concentration',
        'homeroom_teacher',
        'academic_year',
        'activation_status',
        'activation_password',
        'activation_requested_at',
        'activated_at',
        'activation_note',
        'user_id',
    ];

    protected $hidden = [
        'activation_password',
    ];

    protected $casts = [
        'activation_requested_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->activation_status) {
            self::PENDING => 'Menunggu ACC',
            self::ACTIVE => 'Aktif',
            self::REJECTED => 'Ditolak',
            default => 'Belum Aktivasi',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->activation_status) {
            self::PENDING => 'bg-amber-100 text-amber-700',
            self::ACTIVE => 'bg-emerald-100 text-emerald-700',
            self::REJECTED => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

    /**
     * Pending paling atas, lalu ditolak, belum aktivasi, dan yang sudah aktif paling bawah.
     */
    public function scopePendingFirst($query)
    {
        return $query->orderByRaw(
            "CASE activation_status
                WHEN 'pending' THEN 0
                WHEN 'rejected' THEN 1
                WHEN 'inactive' THEN 2
                ELSE 3
            END"
        );
    }
}
