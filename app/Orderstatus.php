<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orderstatus extends Model {
    
    protected $table = "order_status";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','status_name', 'created_at', 'updated_at'];
}
