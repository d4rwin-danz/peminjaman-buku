<!DOCTYPE html>
<html lang="id">
<body style="margin:0;padding:24px;background:#F4F5F7;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center">
            <table role="presentation" width="520" cellpadding="0" cellspacing="0" style="max-width:520px;width:100%;background:#ffffff;border-radius:10px;border:1px solid #e2e8f0;">
                <tr><td style="background:#253F6B;padding:20px 28px;border-radius:10px 10px 0 0;">
                    <p style="margin:0;font-size:12px;letter-spacing:2px;color:#E2B13C;font-weight:bold;">E-LIBRARY</p>
                    <h1 style="margin:6px 0 0;font-size:20px;color:#ffffff;">Permintaan aktivasi akun baru</h1>
                </td></tr>
                <tr><td style="padding:24px 28px;">
                    <p style="margin:0 0 16px;font-size:15px;line-height:1.6;">
                        Seorang {{ strtolower($typeLabel) }} baru saja mengajukan aktivasi akun dan menunggu persetujuanmu.
                    </p>

                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;font-size:14px;">
                        <tr><td style="padding:10px 14px;color:#64748b;width:38%;border-bottom:1px solid #f1f5f9;">Nama</td>
                            <td style="padding:10px 14px;font-weight:bold;border-bottom:1px solid #f1f5f9;">{{ $account->name }}</td></tr>
                        <tr><td style="padding:10px 14px;color:#64748b;border-bottom:1px solid #f1f5f9;">{{ $identityLabel }}</td>
                            <td style="padding:10px 14px;font-weight:bold;border-bottom:1px solid #f1f5f9;">{{ $identity }}</td></tr>
                        @if ($isStudent && $account->class_name)
                            <tr><td style="padding:10px 14px;color:#64748b;border-bottom:1px solid #f1f5f9;">Kelas</td>
                                <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">{{ $account->class_name }}</td></tr>
                        @elseif (! $isStudent && $account->position)
                            <tr><td style="padding:10px 14px;color:#64748b;border-bottom:1px solid #f1f5f9;">Jabatan</td>
                                <td style="padding:10px 14px;border-bottom:1px solid #f1f5f9;">{{ $account->position }}</td></tr>
                        @endif
                        <tr><td style="padding:10px 14px;color:#64748b;">Diajukan</td>
                            <td style="padding:10px 14px;">{{ optional($account->activation_requested_at)->format('d M Y H:i') ?? now()->format('d M Y H:i') }}</td></tr>
                    </table>

                    <p style="margin:24px 0;text-align:center;">
                        <a href="{{ $reviewUrl }}" style="display:inline-block;background:#E2B13C;color:#172641;text-decoration:none;font-weight:bold;font-size:15px;padding:12px 28px;border-radius:8px;">
                            Tinjau &amp; ACC Sekarang
                        </a>
                    </p>

                    <p style="margin:0;font-size:12px;line-height:1.6;color:#94a3b8;">
                        Jika tombol tidak berfungsi, buka tautan ini:<br>
                        <a href="{{ $reviewUrl }}" style="color:#456497;word-break:break-all;">{{ $reviewUrl }}</a>
                    </p>
                </td></tr>
            </table>
            <p style="margin:16px 0 0;font-size:12px;color:#94a3b8;">Email otomatis dari sistem E-Library. Jangan dibalas.</p>
        </td></tr>
    </table>
</body>
</html>
