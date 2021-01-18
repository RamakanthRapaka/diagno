<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderfiles extends Model {
    
    protected $table = "order_files";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','user_id','order_id', 'file', 'created_at', 'updated_at'];
    
    /*public function getFileAttribute($value)
    {
        if(isset($value))
        {
        return $this->attributes['file'] = 'http://13.232.64.138/uploads/'.$value;
        }
        else
        {
        return $this->attributes['file'] = '';   
        }
    }*/
}
