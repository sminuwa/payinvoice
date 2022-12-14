<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\Api\eNaira;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const TYPE_USER = 'USER';
    const TYPE_MERCHANT = 'MERCHANT';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'alias',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
//        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public function getUpdatedAtAttribute($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    public static function login($phone, $password){
        $user = self::where('phone', $phone)->first();
        $type = $user->type;
        $email = $user->email;
        if($login = eNaira::login($email, $password, $type)){
            if($login['status'] == 1)
                if($user->alias == '' || $user->alias == null){ $user->update(['alias' => $login['data']['alias']]);}
            return $login['data']['token'];
        }
        return false;
    }
}
