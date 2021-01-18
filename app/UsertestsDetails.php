<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsertestsDetails extends Model {

    protected $table = "user_tests_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id', 'user_id', 'order_id', 'test_name', 'price', 'created_at', 'updated_at'
    ];
}
