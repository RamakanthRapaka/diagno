<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Orderstatus;

class Usertests extends Model {

    protected $table = "users_tests";
    protected $appends = array('mobile','order_status_name');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id', 'user_id', 'order_id', 'order_status', 'created_at', 'updated_at'
    ];

    public function getCreatedAtAttribute($value) {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }

    public function getMobileAttribute($value) {
        $user = User::where('id', $this->user_id)->first();
        if (isset($user)) {
            return $this->attributes['mobile'] = $user->mobile;
        }
        if (!isset($user)) {
            return $this->attributes['mobile'] = $value;
        }
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function statusname() {
        return $this->hasMany('App\Orderstatus', 'id', 'order_status');
    }

    public function testfiles() {
        return $this->hasMany('App\Usertestsfiles', 'order_id', 'order_id');
    }
    
    public function getOrderStatusNameAttribute() {
        if ($this->order_status != NULL) {
            $order_status = Orderstatus::where('id', $this->order_status)->first();
            if ($order_status != NULL) {
                return $this->attributes['order_status_name'] = $order_status->status_name;
            } else {
                return $this->attributes['order_status_name'] = $this->order_status;
            }
        } else {
            return $this->attributes['order_status_name'] = $this->order_status;
        }
    }

}
