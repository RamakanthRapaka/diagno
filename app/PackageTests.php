<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageTests extends Model
{
    protected $table = "package_tests";
    
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','package_id','test_profile'];
}
