<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseOutcome extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $hidden = [
        'created_at','updated_at'
    ];

    public function relSubject(){
        return $this->hasOne(CourseAssign::class,'co_id');
    }
}
