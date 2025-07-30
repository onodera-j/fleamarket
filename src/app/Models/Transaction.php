<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'purchaser_id',
        'post_code',
        'address',
        'building',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"purchaser_id","id");
    }

    public function item()
    {
        return $this->belongsTo(Item::class,"item_id","id");
    }


}
