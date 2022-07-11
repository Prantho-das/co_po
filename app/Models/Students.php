<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Students extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
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
