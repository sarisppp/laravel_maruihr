<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Course;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Register extends Model
{
    use HasFactory;

    public function course()
    {
        return $this->belongsTo(Course::class,'idcourse');

        //   return $this->belongsTo(Course::class);
      
    }

    public function users()
    {
        return $this->belongsTo(User::class,'iduser');

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'idcourse',
        'iduser',
        'status',
    ];
}
