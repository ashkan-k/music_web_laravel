<?php

namespace App\Models;

use App\Notifications\UserVerificationMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'is_used'
    ];

    public function generateCode($codeLength = 6)
    {
        $max = pow(10, $codeLength);
        $min = $max / 10 - 1;
        $code = mt_rand($min, $max);
        return $code;
    }

    public function __construct(array $attributes = [])
    {
        if (!isset($attributes['code'])) {
            $attributes['code'] = $this->generateCode();
        }
        parent::__construct($attributes);
    }

    public function send_code()
    {
        if (!$this->user) {
            throw new \Exception("No user attached to this token.");
        }
        if (!$this->code) {
            $this->code = $this->generateCode();
        }

        try {
            $this->user->notify(new UserVerificationMail());
            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
