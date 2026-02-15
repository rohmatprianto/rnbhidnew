<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPhotoOrder extends Model
{
    protected $fillable = [
        'event_photo_id',
        'guardian_name',
        'email',
        'phone',
        'rider_full_name',
        'rider_nickname',
        'category',
        'plate_no',
        'batch',
        'instagram',
    ];

    public function eventPhoto(): BelongsTo
    {
        return $this->belongsTo(EventPhoto::class, 'event_photo_id');
    }
}
