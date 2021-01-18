<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userspatient extends Model
{
    protected $table = "users_patient";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id','user_id','patient_age','patient_gender','patient_name','created_at','updated_at'
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
