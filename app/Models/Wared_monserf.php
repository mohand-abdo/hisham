<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wared_monserf extends Model
{
    protected $fillable = ['user_id', 'from' , 'to', 'type', 'cash','bank_id','note'];

    public function expense(){
        return $this->belongsTo(Expense::class,'type');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

}
