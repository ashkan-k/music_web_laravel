<?php

namespace App\Models;

use App\Http\Traits\Uploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends BaseModel
{
    use HasFactory, Uploader;

    protected $fillable = ['user_id', 'file'];
    protected $default = ['user_id', 'file', 'user_data', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['user_data'];

    public static $bulk_actions = [
      'delete'
    ];

    public function getUserDataAttribute($value)
    {
        return $this->user;
    }

    public function setFileAttribute($value)
    {
        $cover = $this->UploadFile($value, 'uploads', auth()->user()->email);
        $this->attributes['file'] = $cover;
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

    public function save(array $options = [])
    {
        $this->attributes['user_id'] = auth()->user()->id;

        parent::save($options);
    }

    ##############################################################

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
