<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserCRM extends Authenticatable implements JWTSubject
// class UserCRM extends Model
{
                        
    
    public    $timestamps   = false;

    protected $table        = 'crm_user';
    protected $fillable     = ['UUID_USER','UUID_RULE','USERNAME',
                                'PASSWORD','NAME','EMAIL','PHONE','GENDER',
                                'BIRTH_DAY','AVATAR', 'ADDRESS','USER_TOKEN','NOTIFY_TOKEN'];
    protected $guarded      = ['UUID_USER'];

    // protected $primaryKey   = ['UUID_USER'];
    public    $incrementing = false;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
