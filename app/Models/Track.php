<?php

namespace App\Models;

use App\Http\Traits\Uploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends BaseModel
{
    use HasFactory, Uploader;

    protected $fillable = ['name', 'is_vip', 'cover', 'file', 'is_file_link', 'singer_id', 'album_id', 'genre_id', 'lyrics', 'published_date'];
    protected $default = ['name', 'is_vip', 'cover', 'file', 'is_file_link', 'singer_id', 'album_id', 'genre_id', 'singer', 'genre', 'album_name', 'created_at', 'lyrics', 'published_date'];
    protected $hidden = ["updated_at", "pivot"];
    protected $appends = ['album_name'];

    public static $bulk_actions = [
        'delete', 'cash', 'free'
    ];

    public function setCoverAttribute($value)
    {
        $cover = $this->UploadFile($value, 'tracks', $this->name);
        $this->attributes['cover'] = $cover;
    }

    public function setFileAttribute($value)
    {
        if (request()->hasFile('file')) {
            $file = $this->UploadFile($value, 'track_music_file', $this->name);
            $this->attributes['file'] = $file;
        } else {
            $this->attributes['file'] = $value;
        }
    }

    public function getAlbumNameAttribute($value)
    {
        return $this->album ? $this->album->name : null;
    }

    public function save(array $options = [])
    {
        if (request()->post('published_date') == null) {
            $this->attributes['published_date'] = null;
        }
        if (request()->post('album_id') == null) {
            $this->attributes['album_id'] = null;
        }
        if (request()->post('delete_file')) {
            $this->attributes['cover'] = null;
        }
        if (request()->post('delete_track_file')) {
            $this->attributes['file'] = null;
        }

        parent::save($options);
    }

    public static function query()
    {
        $query = parent::query();
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%')
                ->OrWhere('lyrics', 'like', '%' . request('search') . '%')
                ->OrWhereHas('album', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })
                ->OrWhereHas('singer', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                })
                ->OrWhereHas('genre', function ($query) {
                    $query->where('name', 'like', '%' . request('search') . '%');
                });
        }

        if (request('type') != "") {
            $query->where('is_vip', request('type'));
        }

        if (request('genre') != "") {
            $query->where('genre_id', request('genre'));
        }
        return $query;
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

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
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
        return $this->morphMany(WishList::class, 'wish_listable');
    }
}
