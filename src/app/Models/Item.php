<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition',
        'item_name',
        'brand_name',
        'item_detail',
        'price',
        'user_id',
        'item_image',
        'soldout',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,user_id,id);
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }
    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where("item_name" , "like", "%" . $keyword . "%");
        }
    }

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }


}
