<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Lesson extends Model
{
    use GlobalStatus;

    protected $casts = ['temp_file' => 'object'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function premiumBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->premiumBadgeData(),
        );
    }


    public function premiumBadgeData()
    {
        $html = '';
        if ($this->premium == Status::YES) {
            $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
        } else {
            $html = '<span><span class="badge badge--warning">' . trans('No') . '</span></span>';
        }
        return $html;
    }
}
