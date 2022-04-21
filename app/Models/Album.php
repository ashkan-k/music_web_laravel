<?php

namespace App\Models;

use App\Http\Traits\Uploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends BaseModel
{
    use HasFactory, Uploader;

    protected $fillable = ['name', 'published_date', 'description', 'is_vip', 'cover', 'singer_id'];
    protected $default = ['name', 'published_date', 'description', 'is_vip', 'cover', 'singer_id', 'singer', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];

    public static $bulk_actions = [
        'delete', 'cash', 'free'
    ];

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->where('name', 'like', '%' . request('search') . '%')
            ->OrWhere('description', 'like', '%' . request('search') . '%')
            ->OrWhereHas('singer', function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
                $query->OrWhere('bio', 'like', '%' . request('search') . '%');
            });
        }

        if (request('type') != "")
        {
            $query->where('is_vip', request('type'));
        }
        return $query;
    }

    public function setCoverAttribute($value)
    {
        $cover = $this->UploadFile($value, 'albums', $this->name);
        $this->attributes['cover'] = $cover;
    }

    public function save(array $options = [])
    {
        if (request()->post('published_date') == null)
        {
            $this->attributes['published_date'] = null;
        }

        if (request()->post('delete_file'))
        {
            $this->attributes['cover'] = null;
        }

        parent::save($options);
    }

    ##############################################################

    public function scopeChangeType($query, $is_vip)
    {
        $query->update(['is_vip' => $is_vip]);
        return $query;
    }

    ##############################################################

    public function singer()
    {
        return $this->belongsTo(Singer::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function like_dislikes()
    {
        return $this->morphMany(LikeDislike::class, 'like_dislike_able');
    }

    public function wish_lists()
    {
        return $this->morphMany(WishList::class , 'wish_listable');
    }
}
