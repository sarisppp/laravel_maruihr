<?php

namespace App\Models;

use App\Models\Register;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Course extends Model
{
    use HasFactory;


    public function registers()
    {

        return $this->hasMany(Register::class,'idcourse','_id');


    }


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'course',
        'description',
        'speaker',
        'category',
        'place',
        'hour',
        'date',
        'time',
        'limited',
        'registed'
    ];
}
