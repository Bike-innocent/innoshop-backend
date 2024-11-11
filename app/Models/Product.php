<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a unique slug when a product is created
        static::creating(function ($product) {
            $product->slug = self::generateUniqueSlug();
        });
    }

    public static function generateUniqueSlug()
    {
        do {
            $slug = Str::random(10);
        } while (self::where('slug', $slug)->exists());

        return $slug;
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colour()
    {
        return $this->belongsTo(Colour::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }



public function supplier()
{
    return $this->belongsTo(User::class, 'supplier_id');
}


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }
}
