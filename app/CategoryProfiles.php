<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProfiles extends Model
{
    protected $table = "category_profiles";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','category_id','profile_id','active','created_at','updated_at'];
    
    public function getCreatedAtAttribute($value)
    {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }
}
