<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'user_id',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'borrowing_proof',
        'return_proof',
        'late_days',
        'fine',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine' => 'integer',
        'late_days' => 'integer',
    ];

    /**
     * Otomatis membuat kode peminjaman saat transaksi baru dibuat.
     */
    protected static function booted(): void
    {
        static::creating(function (Loan $loan) {
            if (empty($loan->loan_code)) {
                $loan->loan_code = static::generateUniqueCode($loan->loan_date);
            }
        });
    }

    /**
     * Membuat kode unik, contoh: PJM-20261102-A7K3QX
     * Huruf/angka yang mudah tertukar (O, 0, I, 1) sengaja tidak dipakai.
     */
    public static function generateUniqueCode($date = null): string
    {
        $datePart = ($date ? Carbon::parse($date) : now())->format('Ymd');
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $max = strlen($alphabet) - 1;

        do {
            $suffix = '';

            for ($i = 0; $i < 6; $i++) {
                $suffix .= $alphabet[random_int(0, $max)];
            }

            $code = "PJM-{$datePart}-{$suffix}";
        } while (static::where('loan_code', $code)->exists());

        return $code;
    }

    /**
     * Pastikan peminjaman lama (sebelum fitur ini ada) juga punya kode.
     */
    public function ensureLoanCode(): string
    {
        if (empty($this->loan_code)) {
            $this->loan_code = static::generateUniqueCode($this->loan_date);
            $this->saveQuietly();
        }

        return $this->loan_code;
    }

    /**
     * Peminjaman dimiliki oleh satu user/siswa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Satu peminjaman memiliki banyak detail buku.
     */
    public function details(): HasMany
    {
        return $this->hasMany(LoanDetail::class);
    }

    /**
     * Urutan prioritas untuk admin: yang perlu diurus di atas, yang selesai di bawah.
     * 0 pengembalian menunggu konfirmasi, 1 pending, 2 disetujui (data lama),
     * 3 dipinjam & terlambat, 4 dipinjam, 5 dikembalikan, 6 ditolak.
     */
    public function scopePriorityOrder($query)
    {
        return $query
            ->orderByRaw(
                "CASE
                    WHEN status = 'return_pending' THEN 0
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'approved' THEN 2
                    WHEN status = 'borrowed' AND due_date < ? THEN 3
                    WHEN status = 'borrowed' THEN 4
                    WHEN status = 'returned' THEN 5
                    ELSE 6
                END",
                [now()->toDateString()]
            )
            ->orderByDesc('created_at');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'borrowed'
            && $this->due_date
            && $this->due_date->lt(now()->startOfDay());
    }
}
