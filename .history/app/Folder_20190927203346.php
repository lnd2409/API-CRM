<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    public    $timestamps   = false;

    protected $table        = 'crm_folder_management';
    protected $fillable     = ['UUID_FOLDER_MANAGEMENT','NAME_FOLDER','YEAR_FOLDER', "LV_FOLDER","UUID_PARENT"];
    protected $guarded      = ['UUID_FOLDER_MANAGEMENT'];

    protected $primaryKey   = ['UUID_FOLDER_MANAGEMENT'];
    public    $incrementing = false;
}
