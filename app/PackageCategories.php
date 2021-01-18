<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageCategories extends Model
{
    protected $table = "package_categories";
    
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','package_id','category_id'];
}
