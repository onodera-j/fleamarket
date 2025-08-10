<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'seller_id',
        'purchaser_id',
        'seller_status',
        'purchaser_status',
        'mail',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function purchaser()
    {
        return $this->belongsTo(User::class, 'purchaser_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

}
