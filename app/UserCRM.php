<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCRM extends Model
{
    												
    public    $timestamps   = false;

    protected $table        = 'crm_user';
    protected $fillable     = ['UUID_USER','UUID_RULE','USERNAME',
                                'PASSWORD','NAME','EMAIL','PHONE','GENDER',
                                'BIRTH_DAY','AVATAR', 'ADDRESS','USER_TOKEN','NOTIFY_TOKEN'];
    protected $guarded      = ['UUID_USER'];

    protected $primaryKey   = ['UUID_USER'];
    public    $incrementing = false;
}
