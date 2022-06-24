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
            "name" => $row[1],
            "roll" => $row[2],
            "reg_no" => $row[3],
            "shift" => $row[4],
            "batch_id" => $row[5],
            "session_id" => $row[6],
        ]);
    }
}
