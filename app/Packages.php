<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    protected $table = "packages";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','package_id','package_name','package_price','discount_price','package_image','status','package_description','created_at','updated_at'];
    
    public function getCreatedAtAttribute($value)
    {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return $this->attributes['updated_at'] = date('d-M-Y h:i A', strtotime($value));
    }
    
    public function getPackageImageAttribute($value){
    return $this->attributes['package_image'] = 'http://13.232.64.138/uploads/'.$value;
    }
}
