<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAssign extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $table= "exam_assigns";


    public function relCourseAssignCopo(){
        return $this->hasOne(CourseAssign::class, 'id', 'copo_id');
    }
}
