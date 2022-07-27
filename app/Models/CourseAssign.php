<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssign extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function relCo(){
        return $this->hasOne(CourseOutcome::class,'id','co_id');
    }

    public function relPo()
    {
        return $this->hasOne(ProgramOutcome::class, 'id', 'po_id');
    }
    public function relCourse()
    {
        return $this->hasOne(Course::class);
    }
}
