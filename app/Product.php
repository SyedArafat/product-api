<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method create(array $data)
 */
class Product extends Model
{
    protected $guarded = ['id'];
}
