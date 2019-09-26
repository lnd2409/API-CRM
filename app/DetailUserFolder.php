<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailUserFolder extends Model
{
    public    $timestamps   = false;

    protected $table        = 'crm_detail_user_folder';
    protected $fillable     = ['UUID_USER','UUID_FOLDER_MANAGEMENT','YEAR_FOLDER'];
    protected $guarded      = ['UUID_USER'];

    protected $primaryKey   = ['UUID_USER'];
    public    $incrementing = false;
}
