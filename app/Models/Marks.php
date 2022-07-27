<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    public function relStudent()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
    public function relExam()
    {
        return $this->hasOne(ExamAssign::class, 'id', 'exam_id');
    }
}
