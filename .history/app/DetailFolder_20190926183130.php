<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailFolder extends Model
{
    protected $table = "crm_detail_user_folder";
    protected $fillable = ["UUID_USER", "UUID_FOLDER_MANAGEMENT"]
}
