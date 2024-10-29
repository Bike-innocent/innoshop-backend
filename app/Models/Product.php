<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo(ProductCategory::class);
    }
    
    public function brand() {
        return $this->belongsTo(Brand::class);
    }
    
    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
    
}
