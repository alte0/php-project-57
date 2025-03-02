<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Label
 *
 * @property int $id
 */
class Label extends Model
{
    /** @use HasFactory<\Database\Factories\LabelFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];
}
