<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventPhoto extends Model
{
    protected $fillable = [
        'title',
        'location',
        'event_date',
        'status',
        'cover_path',
        'price_notes',
        'category_notes',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(EventPhotoOrder::class, 'event_photo_id');
    }
}
