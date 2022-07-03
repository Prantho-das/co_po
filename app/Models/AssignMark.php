<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignMark extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function relStudent()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
    public function relCourse()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function relMarks()
    {
        return $this->hasMany(Marks::class, 'assign_marks_id');
    }
    public function relCo(){
        return $this->hasOne(CourseOutcome::class, 'id', 'co_id');
    }
    public function relPo()
    {
        return $this->hasOne(ProgramOutcome::class, 'id', 'po_id');
    }
}
