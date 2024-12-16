<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Money_trans extends Model
{
    protected $fillable = ['user_id','from','to','type','bank_id','note'];

}
