<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nis',
        'staff_number',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * Keterangan singkat: kelas untuk siswa, jabatan untuk pegawai.
     */
    public function getUnitLabelAttribute(): ?string
    {
        return $this->student?->class_name ?? $this->staff?->position;
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'pegawai' => 'Pegawai',
            default => 'Siswa',
        };
    }

    /**
     * Identitas yang ditampilkan: NIS untuk siswa, NIP/NIK untuk pegawai, email untuk admin/akun lama.
     */
    public function getIdentifierAttribute(): string
    {
        return $this->nis ?: ($this->staff_number ?: ($this->email ?: '-'));
    }
}
