<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class ProviderMongo extends Model
{
    use HasFactory;
    protected $connection = 'mongodb'; 
    protected $collection = 'providers'; 

    protected $fillable = [
        'name', 'contact', 'status'
    ];

}
