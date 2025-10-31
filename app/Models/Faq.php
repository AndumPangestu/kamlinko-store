<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    protected $fillable = [
        'question',
        'answer',
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
}
