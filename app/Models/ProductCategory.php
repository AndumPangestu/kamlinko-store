<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductCategory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'parent_id',
        'category_link',
        'name',
        'description',
        'put_on_highlight',
        'created_by',
        'updated_by',
    ];

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function admin_create()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function admin_update()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 1080, 1920)
            ->nonQueued();
    }
}
