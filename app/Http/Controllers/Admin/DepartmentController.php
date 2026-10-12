<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    // Tampilkan halaman daftar departemen
    public function index()
    {
        return Inertia::render('Admin/Department', [
            'departments' => Department::latest()->get(),
        ]);
    }

    // Simpan departemen baru
    public function store(Request $request)
    {
        Department::create($this->validateDepartment($request));

        return back()->with('success', 'Departemen berhasil ditambahkan!');
    }

    // Ubah departemen yang sudah ada
    public function update(Request $request, Department $department)
    {
        $department->update($this->validateDepartment($request, $department));

        return back()->with('success', 'Departemen berhasil diperbarui!');
    }

    // Hapus departemen
    public function destroy(Department $department)
    {
        try {
            $department->delete();
        } catch (QueryException $e) {
            // Gagal kalau departemen masih dipakai (misalnya oleh akun pengguna)
            return back()->with('error', 'Departemen tidak bisa dihapus karena masih dipakai.');
        }

        return back()->with('success', 'Departemen berhasil dihapus!');
    }

    // Aturan validasi dipakai bersama oleh store() dan update()
    private function validateDepartment(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'department_code' => [
                'required',
                'string',
                'max:10',
                // Saat update, abaikan departemen itu sendiri supaya tidak dianggap duplikat
                Rule::unique('departments', 'department_code')->ignore($department),
            ],
            'department_name' => ['required', 'string', 'max:255'],
        ], [
            'department_code.required' => 'Kode departemen wajib diisi.',
            'department_code.unique'   => 'Kode departemen ini sudah terdaftar.',
            'department_name.required' => 'Nama departemen wajib diisi.',
        ]);
    }
}
