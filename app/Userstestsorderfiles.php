<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userstestsorderfiles extends Model {

    protected $table = "users_tests_order_files";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id', 'order_id', 'user_id', 'file', 'created_at', 'updated_at'
    ];

    public function getCreatedAtAttribute($value) {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }

}
