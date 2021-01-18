<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = "address";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','user_id','type','flat_number','street_name','locality','land_mark','city','state','pincode','latitude','longitude','created_at','updated_at'];
    
    public function getCreatedAtAttribute($value)
    {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return $this->attributes['updated_at'] = date('d-M-Y h:i A', strtotime($value));
    }
}
