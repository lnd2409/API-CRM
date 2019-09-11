<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public    $timestamps   = false;

    protected $table        = 'crm_rule';
    protected $fillable     = ['UUID_RULE','NAME_RULE','NOTE_RULE'];
    protected $guarded      = ['UUID_RULE'];

    protected $primaryKey   = ['UUID_RULE'];
    public    $incrementing = false;
}
