<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'nisn',
        'name',
        'origin_school',
        'final_score',
        'choice_1',
        'choice_2',
        'status',
        'accepted_major'
    ];
}
