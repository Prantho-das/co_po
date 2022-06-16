<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function relSession()
    {
        return $this->belongsTo(SessionYear::class, 'session_id');
    }
    public function relBatch()
    {
        return $this->belongsTo(StudentBatch::class, 'batch_id');
    }
}
