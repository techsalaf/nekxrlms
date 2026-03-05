<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAccessControl extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'is_locked',
        'updated_by',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
