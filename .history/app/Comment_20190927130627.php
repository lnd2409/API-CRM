<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;
    
    protected $table        = 'crm_comment_file_management';
    protected $fillable     = ['UUID_COMMENT','UUID_FILE_MANAGEMENT', "UUID_USER",'CONTENT_COMMENT','NAME_USER'];
    protected $guarded      = ['UUID_COMMENT'];

    protected $primaryKey   = ['UUID_COMMENT'];
    public    $incrementing = false;
}
