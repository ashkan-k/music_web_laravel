<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'wish_listable_id', 'wish_listable_type'];
    protected $default = ['user_id', 'user_data', 'wish_listed_model', 'wish_listable_id', 'wish_listable_type', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['user_data', 'wish_listed_model'];

    public static $bulk_actions = [
        'delete'
    ];

    public function getUserDataAttribute($value)
    {
        return $this->user;
    }

    public function getWishListedModelAttribute()
    {
        $model_name = [];
        if ($this->wish_listable_type == Album::class){
            $model_name['display'] = "آلبوم " . $this->wish_listable->name;
            $model_name['color'] = 'danger';
        }
        else{
            $model_name['display'] = "آهنگ " . $this->wish_listable->name;
            $model_name['color'] = 'success';
        }
        return $model_name;
    }

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->WhereHas('user', function ($query) {
                    $query->where('first_name', 'like', '%' . request('search') . '%')
                        ->OrWhere('last_name', 'like', '%' . request('search') . '%');
                });
        }
        return $query;
    }

    ##############################################################

    public function wish_listable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
