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
        'is_active',
        'category_id'
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('image')
            ->fit(Fit::Contain)
            ->format('webp')
            ->quality(80);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
