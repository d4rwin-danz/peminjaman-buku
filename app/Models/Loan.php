<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
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
}