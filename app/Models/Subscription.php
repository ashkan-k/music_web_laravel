<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscription extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'expire_time', 'amount'];
    protected $default = ['name', 'slug', 'expire_time', 'amount', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];

    public static $bulk_actions = [
        'delete'
    ];

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->where('name', 'like', '%' . request('search') . '%')
                ->OrWhere('slug', 'like', '%' . request('search') . '%')
                ->OrWhere('expire_time', 'like', '%' . request('search') . '%')
                ->OrWhere('amount', 'like', '%' . request('search') . '%');
        }
        return $query;
    }

    public function save(array $options = [])
    {
        $this->attributes['slug'] = str_replace(' ', '-', request()->post('name'));
        parent::save($options);
    }

    ##############################################################

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }
}
