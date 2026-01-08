<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Category extends Model
{
    use GlobalStatus;

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Course::class);
    }

    public function scopeShowOnBanner($query)
    {
        $query->where('show_on_banner', Status::ENABLE);
    }
    public function scopeActive($query)
    {
        $query->where('status', Status::ENABLE);
    }

    public function scopeFeatured($query)
    {
        $query->where('status', Status::ENABLE)->where('featured', Status::YES);
    }

    public function featuredBadge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->featuredBadgeData(),
        );
    }

    public function featuredBadgeData()
    {
        $html = '';
        if ($this->featured == Status::YES) {
            $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
        } else {
            $html = '<span><span class="badge badge--warning">' . trans('No') . '</span></span>';
        }
        return $html;
    }
    public function showOnBannerBadge(): Attribute
    {
        return new Attribute(
            get: fn () => $this->showOnBannerBadgeData(),
        );
    }

    public function showOnBannerBadgeData()
    {
        $html = '';
        if ($this->show_on_banner == Status::YES) {
            $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
        } else {
            $html = '<span><span class="badge badge--warning">' . trans('No') . '</span></span>';
        }
        return $html;
    }

}
