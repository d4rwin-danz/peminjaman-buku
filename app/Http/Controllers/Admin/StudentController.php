<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $class = $request->input('class');

        $students = Student::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('class_name', 'like', "%{$search}%");
                });
            })
            ->when($status, fn ($query) => $query->where('activation_status', $status))
            ->when($class, fn ($query) => $query->where('class_name', $class))
            ->pendingFirst()
            // Yang paling lama menunggu diurus lebih dulu, sisanya per kelas.
            ->orderBy('activation_requested_at')
            ->orderBy('class_name')
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        $classes = Student::query()->distinct()->orderBy('class_name')->pluck('class_name');

        $counts = [
            'total' => Student::count(),
            'pending' => Student::where('activation_status', Student::PENDING)->count(),
            'active' => Student::where('activation_status', Student::ACTIVE)->count(),
            'inactive' => Student::whereIn('activation_status', [Student::INACTIVE, Student::REJECTED])->count(),
        ];

        return view('admin.students.index', compact('students', 'classes', 'counts', 'search', 'status', 'class'));
    }

    public function create()
    {
        return view('admin.students.form', ['student' => new Student(['academic_year' => '2026/2027'])]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(null));

        Student::create($data + ['activation_status' => Student::INACTIVE]);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        return view('admin.students.form', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate($this->rules($student));
        unset($data['nis']); // NIS adalah primary key, tidak diubah.

        $student->update($data);

        if ($student->user) {
            $student->user->update(['name' => $student->name]);
        }

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->user_id) {
            return back()->with('error', 'Siswa yang sudah pernah aktif tidak bisa dihapus. Nonaktifkan saja.');
        }

        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Data siswa dihapus.');
    }

    /**
     * ACC satu aktivasi.
     */
    public function approve(Student $student)
    {
        try {
            $this->activate($student->nis);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Akun {$student->name} ({$student->nis}) diaktifkan. Siswa bisa login dengan NIS dan password buatannya.");
    }

    /**
     * ACC beberapa aktivasi sekaligus (checkbox).
     */
    public function bulkApprove(Request $request)
    {
        $nis = array_filter((array) $request->input('nis', []));

        if (empty($nis)) {
            return back()->with('error', 'Pilih minimal satu siswa yang akan di-ACC.');
        }

        $ok = 0;
        $skipped = 0;

        foreach ($nis as $n) {
            try {
                $this->activate((string) $n);
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

    public function reject(Request $request, Student $student)
    {
        $data = $request->validate([
            'note' => ['required', 'string', 'max:200'],
        ], [
            'note.required' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($student->activation_status !== Student::PENDING) {
            return back()->with('error', 'Permintaan ini sudah tidak berstatus menunggu.');
        }

        $student->update([
            'activation_status' => Student::REJECTED,
            'activation_password' => null,
            'activation_note' => $data['note'],
        ]);

        return back()->with('success', "Aktivasi {$student->name} ditolak.");
    }

    /**
     * Nonaktifkan akun (mis. lupa password / pindah). Riwayat peminjaman tetap tersimpan.
     * Siswa bisa mengajukan aktivasi lagi dengan password baru.
     */
    public function deactivate(Student $student)
    {
        $student->update([
            'activation_status' => Student::INACTIVE,
            'activation_password' => null,
            'activation_note' => null,
        ]);

        return back()->with('success', "Akun {$student->name} dinonaktifkan. Siswa perlu aktivasi ulang untuk login.");
    }

    private function activate(string $nis): void
    {
        DB::transaction(function () use ($nis) {
            $student = Student::whereKey($nis)->lockForUpdate()->firstOrFail();

            if ($student->activation_status !== Student::PENDING || ! $student->activation_password) {
                throw new \Exception('Permintaan aktivasi ini sudah tidak berstatus menunggu.');
            }

            $user = $student->user_id ? User::find($student->user_id) : null;

            if ($user) {
                // Akun lama dipakai ulang supaya riwayat peminjaman tidak putus.
                $user->forceFill([
                    'name' => $student->name,
                    'nis' => $student->nis,
                    'password' => $student->activation_password,
                ])->save();
            } else {
                $user = User::create([
                    'name' => $student->name,
                    'nis' => $student->nis,
                    'password' => $student->activation_password, // sudah hash
                    'role' => 'siswa',
                ]);
            }

            $student->update([
                'activation_status' => Student::ACTIVE,
                'activation_password' => null,
                'activation_note' => null,
                'activated_at' => now(),
                'user_id' => $user->id,
            ]);
        });
    }

    private function rules(?Student $student): array
    {
        return [
            'nis' => $student
                ? ['sometimes']
                : ['required', 'regex:/^[0-9]{4,10}$/', 'unique:students,nis'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'religion' => ['nullable', 'string', 'max:30'],
            'class_name' => ['required', 'string', 'max:30'],
            'field_of_study' => ['nullable', 'string', 'max:255'],
            'study_program' => ['nullable', 'string', 'max:255'],
            'concentration' => ['nullable', 'string', 'max:255'],
            'homeroom_teacher' => ['nullable', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:20'],
        ];
    }
}
