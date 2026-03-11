<?php

namespace App\Models;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'id',
        'site_title',
        'categories_title',
        'swiper_effect',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('background')
            ->fit(Fit::Contain)
            ->format('webp')
            ->quality(80);
    }
}
