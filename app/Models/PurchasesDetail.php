<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasesDetail extends Model
{
    use HasFactory;

    protected $table = 'purchases_details';
    protected $fillable = [
        'purchases_id',
        'inventories_id',
        'qty',
        'price',
    ];
}
