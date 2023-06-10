<?php

namespace Palmans\Tracker;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Str;

class SiteView extends Eloquent
{
    public $timestamps = false;

    protected $casts = [
        'requested_at' => 'datetime',
        'created_at' => 'datetime',
        'unique' => 'boolean',
    ];

    protected $guarded = [];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function user()
    {
        return $this->belongsTo(config('tracker.user_model', '\App\Models\User'));
    }

    /**
     * Scope for choosing by date
     *
     * @param Builder $query
     * @param timestamp $until
     * @param timestamp|null $from
     * @return Builder
     */
    public function scopeOlderThanOrBetween(Builder $query, $until = null, $from = null)
    {
        if (is_null($until)) {
            $until = Carbon::now();
        }

        $query->where('created_at', '<', $until);

        if (! is_null($from)) {
            $query->where('created_at', '>=', $from);
        }

        return $query;
    }

    public function relativeUrl(): Attribute
    {
        return Attribute::get(fn () => (string)Str::of($this->url)->after(config('app.url'))->start('/') );
    }
}
