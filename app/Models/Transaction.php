<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends BaseModel
{
    use HasFactory;

    protected $fillable = ['amount', 'refID', 'authority', 'status', 'user_id'];
    protected $default = ['amount', 'refID', 'authority', 'status', 'user_id', 'user_data', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['user_data'];

    public static $bulk_actions = [
        'delete', 'active', 'deactive'
    ];

    public function getUserDataAttribute($value)
    {
        return $this->user;
    }

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->where('amount', 'like', '%' . request('search') . '%')
            ->Orwhere('refID', 'like', '%' . request('search') . '%')
            ->Orwhere('status', 'like', '%' . request('search') . '%')
            ->OrWhereHas('user', function ($query) {
                $query->where('first_name', 'like', '%' . request('search') . '%')
                    ->OrWhere('last_name', 'like', '%' . request('search') . '%');
            });
        }

        if (request('status_filter') != "") {
            $query->where('status', request('status_filter'));
        }
        return $query;
    }

    ##############################################################

    public function scopeChangeActiveStatus($query, $is_active)
    {
        $query->update(['is_active' => $is_active]);
        return $query;
    }

    ##############################################################

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
