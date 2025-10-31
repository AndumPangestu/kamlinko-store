<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use UUID, HasFactory;
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'receiver_name',
        'address',
        'longitude',
        'latitude',
        'city',
        'province',
        'subdistrict',
        'zip',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
