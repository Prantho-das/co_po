<?php

namespace App\Imports;

use App\Models\Students;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentImport implements ToModel, WithValidation, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }
    public function rules(): array
    {
        return [
            '3' => 'unique:students,reg_no'
        ];
    }
    public function customValidationMessages()
    {
        return [
            '3.unique' => 'The Regestration Number has already been taken.',
        ];
    }
    public function __construct($batchId, $sessionId, $departmentId, $shift)
    {
        $this->departmentId = $departmentId;
        $this->batchId = $batchId;
        $this->sessionId = $sessionId;
        $this->shift = $shift;
    }

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
            "shift" => $this->shift,
            "batch_id" => $this->batchId,
            "session_id" => $this->sessionId,
            "department_id" => $this->departmentId
        ]);
        return null;
    }
}
