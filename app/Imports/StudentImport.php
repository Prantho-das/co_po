<?php

namespace App\Imports;

use App\Models\Students;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Students([
            "name" => $row[0],
            "roll" => $row[1],
            "reg_no" => $row[2],
            "shift" => $row[3],
            "batch_id" => $row[4],
            "session_id" => $row[5],
        ]);
    }
}
