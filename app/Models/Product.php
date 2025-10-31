<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use UUID, HasFactory;
    public $incrementing = false;
    protected $fillable = [
        'category_id',
        'tag_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'long_description',
        'put_on_highlight',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'put_on_highlight' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }
    public function tag()
    {
        return $this->belongsTo(ProductTag::class, 'tag_id', 'id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function productType()
    {
        return $this->hasMany(ProductType::class, 'product_id', 'id');
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
