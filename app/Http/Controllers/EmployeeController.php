<?php

namespace App\Http\Controllers;

use App\Imports\EmployeeImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{
    public function showImportForm(){
        return view('import');
    }

    public function import(Request $request){
        $request->validate([
            'file'=>'required|mimes:xls,xlsx'
        ]);

        Excel::import( new EmployeeImport(),$request->file('file'));

        return redirect()->back()->with('success', 'Employee data has ben uploaded');
    }
}
