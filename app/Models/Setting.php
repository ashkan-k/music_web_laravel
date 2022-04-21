<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends BaseModel
{
    use HasFactory;
    protected $fillable = ['key', 'value'];
    protected $default = ['key' , 'value', 'created_at'];
    protected $hidden = ["updated_at", "pivot"];

    public static $bulk_actions = [
        'delete'
    ];

    public static function query()
    {
        $query = parent::query();
        if (request('search'))
        {
            $query->where('key', 'like', '%' . request('search') . '%')
            ->OrWhere('value', 'like', '%' . request('search') . '%');
        }
        return $query;
    }

    public static function InitSettings()
    {
        $settings['LOGO'] = parent::firstOrCreate(['key' => 'LOGO'], [
            'key' => 'LOGO',
            'value' => 'assets/admin/dist/img/AdminLTELogo.png'
        ])->value;

        $settings['COPY_RIGHT'] = parent::firstOrCreate(['key' => 'COPY_RIGHT'], [
            'key' => 'COPY_RIGHT',
            'value' => 'تمام حقوق مادی و معنوی این سایت متعلق است به اشکان کریمی است.'
        ])->value;

        $settings['PAGINATION'] = parent::firstOrCreate(['key' => 'PAGINATION'], [
            'key' => 'PAGINATION',
            'value' => 10
        ])->value;

        return $settings;
    }
}
