<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MenuPage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'sort_order',
        'is_active'
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('image')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
