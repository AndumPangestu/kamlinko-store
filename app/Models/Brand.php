<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Brand extends Model implements HasMedia
{
    use UUID, HasFactory, InteractsWithMedia;
    public $incrementing = false;
    protected $fillable = [
        'category_id',
        'name',
        'logo_url',
        'logo_alt',
        'description',
        'created_by',
        'updated_by',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();
    }
    public function admin_create()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function admin_update()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
