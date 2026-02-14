<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model{
    protected $table = 'data'; 
    protected $fillable = [
        'user_id', 
        'sensors_data', 
        'rele_data'
    ];
    
    protected $casts = [
        'sensors_data' => 'array',
        'rele_data' => 'array',
    ];
}
