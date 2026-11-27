<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'student_code', 'group', 'mark', 'status'];

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}