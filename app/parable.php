<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class parable extends Model
{
    protected $table = "crm_parable";
    protected $fillable = ["UUID_PARABLE", "CONTENT_PARABLE"];
}
