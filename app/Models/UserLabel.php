<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLabel extends Model
{
    protected $fillable = ['user_id', 'label_id'];
}
