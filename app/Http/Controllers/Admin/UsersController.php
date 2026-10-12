<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Users;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Users',[
            'users'=>Users::with('department')->latest()->get(), 'departments'=>Department::all(),
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|max:255|unique:users,email',
        'password'      => 'required|string|min:6|max:12',
        'department_id' => 'required|exists:departments,id',
        'role'          => 'required|in:department,it_support',
    ], [
        'name.required'          => 'Nama wajib diisi',
        'email.required'         => 'Email wajib diisi',
        'email.email'            => 'Format email tidak valid, harus memakai @ (contoh: nama@perusahaan.com)',
        'email.unique'           => 'Email sudah ada',
        'password.required'      => 'Password wajib diisi',
        'password.min'           => 'Password minimal 6 karakter',
        'password.max'           => 'Password maksimal 12 karakter',
        'department_id.required' => 'Departemen wajib dipilih',
        'role.required'          => 'Role wajib dipilih',
    ]);

    Users::create($validated);

    return redirect()->back()->with('success', 'Data pengguna berhasil dimasukkan');
}

   public function update(Request $request, Users $users)
{
    $validated = $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($users->id)],
        'password'      => 'nullable|string|min:6|max:12',
        'department_id' => 'required|exists:departments,id',
        'role'          => 'required|in:department,it_support',
    ], [
        'name.required'          => 'Nama wajib diisi',
        'email.required'         => 'Email wajib diisi',
        'email.email'            => 'Format email tidak valid, harus memakai @ (contoh: nama@perusahaan.com)',
        'email.unique'           => 'Email sudah ada',
        'password.min'           => 'Password minimal 6 karakter',
        'password.max'           => 'Password maksimal 12 karakter',
        'department_id.required' => 'Departemen wajib dipilih',
        'role.required'          => 'Role wajib dipilih',
    ]);

    // Password dikosongkan = tidak diganti
    if (empty($validated['password'])) {
        unset($validated['password']);
    }

    $users->update($validated);

    return redirect()->back()->with('success', 'Data pengguna berhasil diubah');
}

    public function destroy(Users $users){
        try{
            $users->delete();
        } catch (QueryException $e){
            return redirect()->back()->with('error','Pengguna tidak bisa dihapus karena masih dipakai');
        }
        return redirect()->back()->with('success','Data pengguna berhasil dihapus');
    }
}
