<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignCourse extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function relTeacher()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function relExam()
    {
        return $this->hasMany(ExamAssign::class, 't_assign_courses_id','id');
    }
    public function relSemester()
    {
        return $this->hasOne(Semester::class,'id', 'semester_id');
    }
    public function relBatch()
    {
        return $this->belongsTo(StudentBatch::class,'batch_id','id');
    }
    public function relSession()
    {
        return $this->hasOne(SessionYear::class, 'id', 'session_id');
    }
    public function relCourse()
    {
        return $this->hasOne(Course::class,'id','course_id');
    }
}
