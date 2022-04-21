<?php

namespace App\Models;

use Froiden\RestAPI\ApiModel;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends ApiModel
{
    use HasFactory;

    public function getCreatedAtAttribute($dates){
        return Verta::instance($dates)->format('H:i %B %d, %Y ');
    }

    public function getUpdatedAtAttribute($dates){
        return Verta::instance($dates)->format('H:i %B %d, %Y ');
    }
}
