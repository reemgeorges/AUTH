<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlacklistedWord extends Model
{
    protected $fillable = ['word'];
    use HasFactory;


}
