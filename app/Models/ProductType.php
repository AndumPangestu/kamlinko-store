<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductType extends Model implements HasMedia
{
    use UUID, HasFactory, InteractsWithMedia;
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'color',
        'price',
        'discount_fixed',
        'discount_percentage',
        'weight',
        'description',
        'stock',
        'total_sales',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
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
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();
    }   
}
