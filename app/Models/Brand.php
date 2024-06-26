<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'image',
        'active',
    ];

    public function category()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
}
