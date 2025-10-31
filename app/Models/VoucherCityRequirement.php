<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherCityRequirement extends Model
{
    protected $table = "voucher_city_requirements";
    protected $fillable = [
        'voucher_id',
        'city'
        ];
        
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }
}
