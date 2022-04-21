<?php

namespace App\Models;

use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public static $bulk_actions = [
        'delete', 'block', 'unblock'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'email_verified_at',
        'is_superuser',
        'is_blocked',
        'password',
        'avatar',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCreatedAtAttribute($dates){
        return Verta::instance($dates)->format('H:i %B %d, %Y ');
    }

    public function scopeFilter($query, $search)
    {
        if ($search)
        {
            $query->where('first_name', 'like', '%' . $search . '%' );
            $query->OrWhere('last_name', 'like', '%' . $search . '%' );
            $query->OrWhere('email', 'like', '%' . $search . '%' );
            $query->OrWhere('phone', 'like', '%' . $search . '%' );
        }
        return $query;
    }

    public function save(array $options = [])
    {
        if (request()->post('delete_file'))
        {
            $this->attributes['avatar'] = null;
        }

        parent::save($options);
    }

    ##############################################################

    public function scopeChangeBlockStatus($query, $is_blocked)
    {
        $query->update(['is_blocked' => $is_blocked]);
        return $query;
    }

    ##############################################################

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function like_dislikes()
    {
        return $this->hasMany(LikeDislike::class);
    }

    public function wish_lists()
    {
        return $this->hasMany(WishList::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscriber::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}
