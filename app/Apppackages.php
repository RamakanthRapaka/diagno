<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apppackages extends Model {

    protected $table = "app_packages";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id', 'package_id', 'package_name', 'price', 'discount_price', 'created_at', 'updated_at'
    ];

}
