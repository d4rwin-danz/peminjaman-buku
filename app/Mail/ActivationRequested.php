<?php

namespace App\Mail;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * Email ke admin: ada siswa/pegawai yang mengajukan aktivasi akun.
 */
class ActivationRequested extends Mailable
{
    public function __construct(public Student|Staff $account)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permintaan Aktivasi Akun Baru: ' . $this->account->name,
        );
    }

    public function content(): Content
    {
        $isStudent = $this->account instanceof Student;

        return new Content(
            view: 'emails.activation-requested',
            with: [
                'account' => $this->account,
                'isStudent' => $isStudent,
                'typeLabel' => $isStudent ? 'Siswa' : 'Pegawai',
                'identityLabel' => $isStudent ? 'NIS' : 'NIP / NIK',
                'identity' => $isStudent
                    ? $this->account->nis
                    : ($this->account->nip ?: $this->account->staff_number),
                'reviewUrl' => route($isStudent ? 'admin.students.index' : 'admin.staff.index', ['status' => 'pending']),
            ],
        );
    }
}
