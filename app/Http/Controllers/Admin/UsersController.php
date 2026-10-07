<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Users;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Users',[
            'users'=>Users::with('department')->latest()->get(), 'departments'=>Department::all(),
        ]);
    }

    public function store(Request $request){
        $validate = $request->validate([
            'name'=>'required|string|max:255',
            'username'=>'required|unique:users,username',
            'password'=>'required|string|min:6|max:12',
            'department_id'=>'required|exists:departments,id',
            'role'=>'required|in:department,it_support'
        ],[
            'name.required'=>'Nama wajib diisi',
            'username.required'=>'Username wajib diisi',
            'username.unique'=>'Username sudah ada',
            'password.required'=>'Password wajib diisi',
            'password.max'=>'Password maksimal 12 karakter',
            'password.min'=>'Password maksimal 6 karakter',
            'department_id.required'=>'Departemen wajib dipilih',
            'role.required'=>'Role wajib dipilih'
        ]);

        Users::create($validate);

        return redirect()->back()->with('success','Data pengguna berhasil dimasukan');
    }

    public function update(Request $request, Users $users){
        $validate = $request->validate([
            'name'=>'required|string|max:255',
            'username'=>'required|unique:users,username,'.$users->id,
            'password'=>'nullable|string|min:6|max:12',
            'department_id'=>'required|exists:departments,id',
            'role'=>'required|in:department,it_support'
        ],[
            'name.required'=>'Nama wajib diisi',
            'username.required'=>'Username wajib diisi',
            'username.unique'=>'Username sudah ada',
            'password.max'=>'Password maksimal 12 karakter',
            'password.min'=>'Password maksimal 6 karakter',
            'department_id.required'=>'Departemen wajib dipilih',
            'role.required'=>'Role wajib dipilih'
        ]);

        if (empty($validate['password'])){
            unset($validate['password']);
        }

        $users->update($validate);

        return redirect()->back()->with('success','Data pengguna berhasil diubah');
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
