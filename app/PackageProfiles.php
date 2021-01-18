<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageProfiles extends Model
{
    protected $table = "package_profiles";
    
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','package_id','profile_id'];
}
