<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function relCo(){
        return $this->hasOne(CourseOutcome::class, 'id', 'co_id');
    }
    public function relPo()
    {
        return $this->hasOne(ProgramOutcome::class, 'id', 'co_id');
    }
}
