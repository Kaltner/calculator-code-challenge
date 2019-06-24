<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class CalculationElement extends Model
{
    protected $fillable = array('element');
    public $timestamps = false;
}