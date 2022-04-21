<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'commentable_id', 'commentable_type', 'body', 'status'];
    protected $default = ['user_id', 'user_data', 'comment_status', 'status', 'commentable_id', 'commentable_type', 'body', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['user_data', 'comment_status'];

    public static $bulk_actions = [
        'delete', 'approve_status', 'reject_status'
    ];

    public function getUserDataAttribute($value)
    {
        return $this->user;
    }

    public function getCommentStatusAttribute()
    {
        $status = [];
        if ($this->status == 'PD'){
            $status['display'] = 'در انتظار تایید';
            $status['color'] = 'warning';
        }
        else if ($this->status == 'RJ'){
            $status['display'] = 'رد شده';
            $status['color'] = 'danger';
        }
        else{
            $status['display'] = 'تایید شده';
            $status['color'] = 'success';
        }
        return $status;
    }

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->where('body', 'like', '%' . request('search') . '%')
                ->OrWhereHas('user', function ($query) {
                    $query->where('first_name', 'like', '%' . request('search') . '%')
                    ->OrWhere('last_name', 'like', '%' . request('search') . '%');
                });
        }

        if (request('status_filter') != "")
        {
            $query->where('status', request('status_filter'));
        }
        return $query;
    }

    ##############################################################

    public function scopeChangeStatus($query, $new_status)
    {
        $query->update(['status' => $new_status]);
        return $query;
    }

    ##############################################################

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
