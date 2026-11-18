<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Staff extends Model
{
    protected $table = 'staffs';

    protected $primaryKey = 'staff_number';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'staff_number',
        'name',
        'gender',
        'religion',
        'position',
        'employment_status',
        'nip',
        'rank_name',
        'grade',
        'unit',
        'phone',
        'note',
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
            Student::PENDING => 'Menunggu ACC',
            Student::ACTIVE => 'Aktif',
            Student::REJECTED => 'Ditolak',
            default => 'Belum Aktivasi',
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->activation_status) {
            Student::PENDING => 'bg-amber-100 text-amber-700',
            Student::ACTIVE => 'bg-emerald-100 text-emerald-700',
            Student::REJECTED => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-600',
        };
    }

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
