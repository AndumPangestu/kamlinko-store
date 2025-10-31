<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Transaction extends Model implements HasMedia
{
    use UUID, HasFactory, InteractsWithMedia;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'voucher_id',
        'transaction_status',
        'user_address_id',
        'payment_method',
        'delivery_method',
        'delivery_date',
        'delivery_fee',
        'branch_store',
        'tracking_number',
        'total_discount',
        'subtotal',
        'tax',
        'total',
        'total_weight',
        'action_taken_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItems::class, 'transaction_id', 'id');
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class, 'user_address_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'action_taken_by', 'id');

    }
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 1920, 1080)
            ->nonQueued();
    }

}
