<?php

namespace App\Models;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    protected $keyType = 'string';
    public $incrementing = false;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->width(150)
            ->height(150)
            ->sharpen(10);
    }
}
