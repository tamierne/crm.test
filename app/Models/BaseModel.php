<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BaseModel extends Model
{

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('m/d/Y');
    }

    public function getDeletedAtAttribute($value)
    {
        if($value) {
            return Carbon::parse($value)->format('m/d/Y');
        } else {
            return $value;
        }

    }
}
