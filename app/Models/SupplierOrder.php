<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    use HasFactory;

    

public function supplier()
{
    return $this->belongsTo(User::class, 'supplier_id');
}

}
