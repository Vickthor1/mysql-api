<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
     protected $fillable = [ 
    'name', 
    'price', 
    'stock', 
    'barcode',
    'external_source', 
    'external_id',
    'is_external',
    'image' 
    ]; 
}