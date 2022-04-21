<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeDislike extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'like_dislike_able_id', 'like_dislike_able_type', 'type'];
    protected $default = ['user_id', 'like_dislike_able_id', 'like_dislike_able_type', 'user_data', 'type_info', 'like_dislike_model', 'type', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['user_data', 'type_info', 'like_dislike_model'];

    public static $bulk_actions = [
        'delete'
    ];

    public function getUserDataAttribute($value)
    {
        return $this->user;
    }

    public function getTypeInfoAttribute()
    {
        $type = [];
        if ($this->type == 'LIKE'){
            $type['display'] = 'لایک';
            $type['color'] = 'success';
        }
        else{
            $type['display'] = 'دیس لایک';
            $type['color'] = 'danger';
        }
        return $type;
    }

    public function getLikeDislikeModelAttribute()
    {
        $model_name = [];
        if ($this->like_dislike_able_type == Album::class){
            $model_name['display'] = "آلبوم " . $this->like_dislike_able->name;
            $model_name['color'] = 'danger';
        }
        else{
            $model_name['display'] = "آهنگ " . $this->like_dislike_able->name;
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

        if (request('type_filter') != "")
        {
            $query->where('type', request('type_filter'));
        }
        return $query;
    }

    ##############################################################

    public function like_dislike_able()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
