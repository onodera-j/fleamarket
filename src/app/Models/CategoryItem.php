<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    protected $table = "category_items";

    protected $fillable = [
        'item_id',
        'category_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class)->withTimestamps();
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
