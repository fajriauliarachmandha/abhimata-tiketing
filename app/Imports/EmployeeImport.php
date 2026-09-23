<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Model|null
    {
        return new Employee([
            'name'=>$row['name'],
            'telp'=>$row['telp'],
        ]);
    }
}
