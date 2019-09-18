<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public    $timestamps   = false;    

    protected $table = 'crm_history';
    protected $fillable = ['UUID_HISTORY','UUID_USER','NAME_HISTORY','NOTE_HISTORY'];

    protected $guarded      = ['UUID_HISTORY'];

    protected $primaryKey   = ['UUID_HISTORY'];
    public    $incrementing = false;
}
