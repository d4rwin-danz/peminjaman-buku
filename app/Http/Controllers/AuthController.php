<?php

namespace App\Http\Controllers;

use App\Mail\ActivationRequested;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login: siswa memakai NIS, admin memakai email.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ], [
            'login.required' => 'NIS/NIP/NIK wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $login = trim($data['login']);
        $isNumber = ctype_digit($login);

        if ($isNumber) {
            $account = $this->findAccount($login);

            if (! $account) {
                return $this->loginFailed('NIS/NIP/NIK tidak ditemukan. Periksa kembali atau hubungi admin perpustakaan.', $login);
            }

            if ($account->activation_status === Student::PENDING) {
                return $this->loginFailed('Aktivasi akunmu masih menunggu persetujuan admin. Coba lagi setelah disetujui.', $login);
            }

            if ($account->activation_status !== Student::ACTIVE) {
                $message = $account->activation_status === Student::REJECTED
                    ? 'Permintaan aktivasi ditolak' . ($account->activation_note ? ": {$account->activation_note}." : '.') . ' Silakan ajukan aktivasi lagi.'
                    : 'Akun belum diaktivasi. Lakukan aktivasi akun terlebih dahulu.';

                return $this->loginFailed($message, $login);
            }
        }

        $field = $isNumber ? ($account instanceof Staff ? 'staff_number' : 'nis') : 'email';

        $credentials = [
            $field => $login,
            'password' => $data['password'],
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectAfterAuth($request);
        }

        return $this->loginFailed('NIS/NIP/NIK atau password salah.', $login);
    }

    /**
     * NIP pegawai 18 digit, NIK 16 digit, NIS siswa lebih pendek.
     */
    private function findAccount(string $number): Student|Staff|null
    {
        return in_array(strlen($number), [16, 18], true)
            ? Staff::find($number)
            : Student::find($number);
    }

    private function loginFailed(string $message, string $login)
    {
        return back()
            ->withErrors(['login' => $message])
            ->withInput(['login' => $login]);
    }

    public function showActivation()
    {
        return view('auth.activate');
    }

    /**
     * Siswa mengajukan aktivasi: NIS + password pilihan sendiri.
     * Akun baru bisa dipakai login setelah admin menekan ACC.
     */
    public function requestActivation(Request $request)
    {
        $data = $request->validate([
            'identity' => ['required', 'regex:/^([0-9]{4,10}|[0-9]{16}|[0-9]{18})$/'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'identity.required' => 'NIS/NIP/NIK wajib diisi.',
            'identity.regex' => 'NIS (4-10 digit), NIK (16 digit) atau NIP (18 digit) hanya boleh berisi angka.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $account = $this->findAccount($data['identity']);

        if (! $account) {
            return back()
                ->withErrors(['identity' => 'NIS/NIP/NIK tidak ditemukan di data sekolah. Periksa kembali atau hubungi admin perpustakaan agar datamu ditambahkan.'])
                ->withInput($request->only('identity'));
        }

        if ($account->activation_status === Student::ACTIVE) {
            return back()
                ->withErrors(['identity' => 'Akun dengan nomor ini sudah aktif. Silakan login.'])
                ->withInput($request->only('identity'));
        }

        if ($account->activation_status === Student::PENDING) {
            return back()
                ->withErrors(['identity' => 'Permintaan aktivasi sudah dikirim dan sedang menunggu persetujuan admin.'])
                ->withInput($request->only('identity'));
        }

        $account->update([
            'activation_status' => Student::PENDING,
            'activation_password' => Hash::make($data['password']),
            'activation_requested_at' => now(),
            'activation_note' => null,
        ]);

        $this->notifyAdminsOfActivation($account);

        return redirect()
            ->route('login')
            ->with('success', 'Permintaan aktivasi terkirim. Setelah admin menyetujui, login memakai NIS/NIP/NIK dan password yang kamu buat.');
    }

    /**
     * Kirim email ke admin agar segera memproses aktivasi.
     * Dikirim langsung (tanpa antrean) dan tidak boleh menggagalkan aktivasi bila email bermasalah.
     */
    private function notifyAdminsOfActivation(Student|Staff $account): void
    {
        $recipients = array_filter(array_map('trim', explode(',', (string) config('library.admin_notify_email'))));

        if (! $recipients) {
            $recipients = User::where('role', 'admin')
                ->whereNotNull('email')
                ->pluck('email')
                ->all();
        }

        $recipients = array_values(array_unique(array_filter(
            $recipients,
            fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
        )));

        if (! $recipients) {
            return;
        }

        try {
            Mail::to($recipients)->send(new ActivationRequested($account));
        } catch (\Throwable $e) {
            Log::warning('Email notifikasi aktivasi gagal dikirim: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Setelah login: kembali ke halaman yang tadi dituju
     * (mis. form peminjaman buku), atau ke dashboard sesuai role.
     */
    private function redirectAfterAuth(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        $intended = $request->session()->get('url.intended');

        // Siswa tidak boleh diarahkan ke halaman admin.
        if ($intended && str_starts_with(parse_url($intended, PHP_URL_PATH) ?? '', '/admin')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('siswa.dashboard'));
    }
}
