<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use GlobalStatus;

    protected $casts = ['meta_keyword' => 'array', 'learns' => 'object', 'includes' => 'array'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function coursePurchases()
    {
        return $this->hasMany(CoursePurchase::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        $query->where('status', Status::ENABLE)->whereHas('category', function($category){
            $category->active();
        });
    }

    public function scopeFree($query)
    {
        $query->where('premium', Status::NO);
    }

    public function scopePremium($query)
    {
        $query->where('premium', Status::YES);
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

    public function featuredBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->featuredBadgeData(),
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
}
