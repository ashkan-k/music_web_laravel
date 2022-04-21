<?php

namespace App\Models;

use Carbon\Carbon;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'subscription_id', 'is_active'];
    protected $default = ['user_id', 'subscription_id', 'is_active', 'user_data', 'subscription_data', 'expire_date', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['user_data', 'subscription_data', 'expire_date'];

    public static $bulk_actions = [
        'delete', 'active', 'deactive'
    ];

    public function getUserDataAttribute($value)
    {
        return $this->user;
    }

    public function getSubscriptionDataAttribute($value)
    {
        return $this->subscription;
    }

    public function getExpireDateAttribute($value)
    {
        $expire_date = date('Y-m-d H:i', strtotime("+{$this->subscription->expire_time} months", strtotime(Carbon::now())));
        return Verta::instance($expire_date)->format('H:i %B %d, %Y ');
    }

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->WhereHas('user', function ($query) {
                $query->where('first_name', 'like', '%' . request('search') . '%')
                    ->OrWhere('last_name', 'like', '%' . request('search') . '%');
            })->OrWhereHas('subscription', function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
            });
        }

        if (request('active_filter') != "") {
            $query->where('is_active', request('active_filter'));
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

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
