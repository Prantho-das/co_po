<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAssign extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $table= "exam_assigns";

    public function relCo()
    {
        return $this->hasOne(CourseOutcome::class, 'id', 'co_id');
    }
    public function relBatch(){
        return $this->hasOneThrough(StudentBatch::class,TeacherAssignCourse::class,'id','id','t_assign_courses_id','batch_id');
    }

    public function relPo()
    {
        return $this->hasOne(ProgramOutcome::class, 'id', 'po_id');
    }
    public function relCourse()
    {
        return $this->hasOne(Course::class);
    }
    public function relCourseAssignCopo(){
        return $this->hasOne(CourseAssign::class, 'id', 'copo_id');
    }
}
