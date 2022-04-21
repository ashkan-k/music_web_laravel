<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name'];
    protected $default = ['name', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];

    public static $bulk_actions = [
        'delete'
    ];

    public static function GetDefaultGenres()
    {
        $genres = [
            ['name' => 'پاپ'],
            ['name' =>  'سنتی'],
            ['name' => 'پاپ سنتی'],
            ['name' => 'راک'],
            ['name' => 'رپ'],
            ['name' => 'هیپ هاپ'],
            ['name' => 'محلی'],
        ];

        return $genres;
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

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
