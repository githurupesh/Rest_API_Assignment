<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depart extends Model
{
    use HasFactory;
    public $table = "departments";  
    public $timestamps = false;       
    protected $primaryKey = 'dp_id';
}
