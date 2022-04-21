<?php

namespace App\Models;

use App\Http\Traits\Uploader;
use Froiden\RestAPI\ApiModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Singer extends BaseModel
{
    use HasFactory, Uploader;

    protected $fillable = ['name', 'bio', 'image'];
    protected $default = ['name', 'bio', 'image', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];

    public static $bulk_actions = [
        'delete'
    ];

    public function setImageAttribute($value)
    {
        $image = $this->UploadFile($value , 'singers', "{$this->first_name}-$this->last_name");
        $this->attributes['image'] = $image;
    }

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->where('name', 'like', '%' . request('search') . '%')
            ->OrWhere('bio', 'like', '%' . request('search') . '%');
        }
        return $query;
    }

    public function save(array $options = [])
    {
        if (request()->post('delete_file'))
        {
            $this->attributes['image'] = null;
        }

        parent::save($options);
    }

    ########################################################################

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
