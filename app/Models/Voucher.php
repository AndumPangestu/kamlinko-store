<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Voucher extends Model implements HasMedia
{
    use UUID, HasFactory, InteractsWithMedia;
    public $incrementing = false;

    protected $fillable = [
        'voucher_type_id',
        'name',
        'slug',
        'code',
        'value_percentage',
        'value_fixed',
        'minimum_transaction_value',
        'quantity',
        'used',
        'start_date',
        'end_date',
        'description',
        'terms',
        'created_by',
        'updated_by',
    ];

    public function admin_create()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function admin_update()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function voucher_city_requirements()
    {
        return $this->hasMany(VoucherCityRequirement::class);    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();
    }
}
