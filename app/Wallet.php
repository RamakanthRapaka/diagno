<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $table = "wallet";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id','user_id','balance','promotional_balance','created_at','updated_at'
    ];
    
    public function getCreatedAtAttribute($value)
    {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return $this->attributes['updated_at'] = date('d-M-Y h:i A', strtotime($value));
    }
}
