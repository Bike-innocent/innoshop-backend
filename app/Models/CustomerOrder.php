<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    public function customerOrderLines()
{
    return $this->hasMany(CustomerOrderLine::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}


}
