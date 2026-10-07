<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Department',['departments' => Department::latest()->get() ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_code'=>'required|string|max:10|unique:departments,department_code',
            'department_name'=>'required|string|max:255',
        ],[
            'department_code.required'=>'Kode departemen wajib diisi.',
            'department_code.unique'=>'Kode departemen ini sudah terdaftar.',
            'department_name.required'=>'Nama departemen wajib diisi.'

        ]);

        Department::create($validated);

        return redirect()->back()->with('success','Departemen berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validate = $request->validate([
           'department_code'=>'required|string|max:10|unique:departments,department_code,'.$department->id,
            'department_name'=>'required|string|max:255',
        ],[
            'department_code.required'=>'Kode departemen wajib diisi.',
            'department_code.unique'=>'Kode departemen ini sudah terdaftar.',
            'department_name.required'=>'Nama departemen wajib diisi.',
        ]);


        $department->update($validate);

        return redirect()->back()->with('success','Departemen berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
        $department->delete();
    } catch (QueryException $e) {
        return redirect()->back()->with('error', 'Departemen tidak bisa dihapus karena masih dipakai.');
    }

    return redirect()->back()->with('success', 'Departemen berhasil dihapus');
    }
}
