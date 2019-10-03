<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public    $timestamps   = false;

    protected $table        = 'crm_file_management';
    protected $fillable     = ['UUID_FILE_MANAGEMENT','UUID_FOLDER_MANAGEMENT','PATH_FILE','TYPE_FILE','FOLDER_FILE','MONTH_FOLDER','NUMBER_COMMENT'];
    protected $guarded      = ['UUID_FILE_MANAGEMENT'];

    protected $primaryKey   = ['UUID_FILE_MANAGEMENT'];
    public    $incrementing = false;
}
