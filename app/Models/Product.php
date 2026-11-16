<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'size',
        'length',
        'width',
        'material',
        'condition_rating',
        'defect_notes',
        'description',
        'price',
        'image_url',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}