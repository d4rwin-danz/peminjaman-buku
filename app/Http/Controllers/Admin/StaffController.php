<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $position = $request->input('position');

        $staffs = Staff::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('staff_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('activation_status', $status))
            ->when($position, fn ($query) => $query->where('position', $position))
            ->pendingFirst()
            ->orderBy('activation_requested_at')
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        $positions = Staff::query()->distinct()->orderBy('position')->pluck('position');

        $counts = [
            'total' => Staff::count(),
            'pending' => Staff::where('activation_status', Student::PENDING)->count(),
            'active' => Staff::where('activation_status', Student::ACTIVE)->count(),
            'inactive' => Staff::whereIn('activation_status', [Student::INACTIVE, Student::REJECTED])->count(),
        ];

        return view('admin.staff.index', compact('staffs', 'positions', 'counts', 'search', 'status', 'position'));
    }

    public function create()
    {
        return view('admin.staff.form', ['staff' => new Staff()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(null), $this->messages());

        Staff::create($data + ['activation_status' => Student::INACTIVE]);

        return redirect()->route('admin.staff.index')->with('success', 'Data pegawai berhasil ditambahkan. Pegawai tinggal aktivasi dengan NIP/NIK.');
    }

    public function edit(Staff $staff)
    {
        return view('admin.staff.form', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate($this->rules($staff), $this->messages());
        unset($data['staff_number']); // Nomor induk adalah primary key, tidak diubah.

        $staff->update($data);

        if ($staff->user) {
            $staff->user->update(['name' => $staff->name]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Staff $staff)
    {
        if ($staff->user_id) {
            return back()->with('error', 'Pegawai yang sudah pernah aktif tidak bisa dihapus. Nonaktifkan saja.');
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Data pegawai dihapus.');
    }

    public function approve(Staff $staff)
    {
        try {
            $this->activate($staff->staff_number);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Akun {$staff->name} ({$staff->staff_number}) diaktifkan. Pegawai bisa login dengan NIP/NIK dan password buatannya.");
    }

    public function bulkApprove(Request $request)
    {
        $niks = array_filter((array) $request->input('staff_number', []));

        if (empty($niks)) {
            return back()->with('error', 'Pilih minimal satu pegawai yang akan di-ACC.');
        }

        $ok = 0;
        $skipped = 0;

        foreach ($niks as $number) {
            try {
                $this->activate((string) $number);
                $ok++;
            } catch (\Throwable $e) {
                $skipped++;
            }
        }

        $message = "{$ok} akun berhasil diaktifkan.";

        if ($skipped > 0) {
            $message .= " {$skipped} dilewati karena sudah tidak berstatus menunggu.";
        }

        return back()->with('success', $message);
    }

    public function reject(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'note' => ['required', 'string', 'max:200'],
        ], [
            'note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($staff->activation_status !== Student::PENDING) {
            return back()->with('error', 'Permintaan ini sudah tidak berstatus menunggu.');
        }

        $staff->update([
            'activation_status' => Student::REJECTED,
            'activation_password' => null,
            'activation_note' => $data['note'],
        ]);

        return back()->with('success', "Aktivasi {$staff->name} ditolak.");
    }

    public function deactivate(Staff $staff)
    {
        $staff->update([
            'activation_status' => Student::INACTIVE,
            'activation_password' => null,
            'activation_note' => null,
        ]);

        return back()->with('success', "Akun {$staff->name} dinonaktifkan. Pegawai perlu aktivasi ulang untuk login.");
    }

    private function activate(string $number): void
    {
        DB::transaction(function () use ($number) {
            $staff = Staff::whereKey($number)->lockForUpdate()->firstOrFail();

            if ($staff->activation_status !== Student::PENDING || ! $staff->activation_password) {
                throw new \Exception('Permintaan aktivasi ini sudah tidak berstatus menunggu.');
            }

            $user = $staff->user_id ? User::find($staff->user_id) : null;

            if ($user) {
                $user->forceFill([
                    'name' => $staff->name,
                    'staff_number' => $staff->staff_number,
                    'password' => $staff->activation_password,
                ])->save();
            } else {
                $user = User::create([
                    'name' => $staff->name,
                    'staff_number' => $staff->staff_number,
                    'password' => $staff->activation_password, // sudah hash
                    'role' => 'pegawai',
                ]);
            }

            $staff->update([
                'activation_status' => Student::ACTIVE,
                'activation_password' => null,
                'activation_note' => null,
                'activated_at' => now(),
                'user_id' => $user->id,
            ]);
        });
    }

    private function rules(?Staff $staff): array
    {
        return [
            'staff_number' => $staff
                ? ['sometimes']
                : ['required', 'regex:/^([0-9]{16}|[0-9]{18})$/', 'unique:staffs,staff_number'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['L', 'P'])],
            'religion' => ['nullable', 'string', 'max:30'],
            'position' => ['required', 'string', 'max:255'],
            'employment_status' => ['nullable', 'string', 'max:50'],
            'nip' => ['nullable', 'string', 'max:30'],
            'rank_name' => ['nullable', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:20'],
            'unit' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function messages(): array
    {
        return [
            'staff_number.regex' => 'Nomor induk harus NIP (18 digit) atau NIK (16 digit), hanya angka tanpa spasi.',
            'staff_number.unique' => 'Nomor induk ini sudah terdaftar.',
            'staff_number.required' => 'NIP/NIK wajib diisi.',
        ];
    }
}
