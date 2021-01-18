<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Orderstatus;
use App\Services;
use App\Profiles;
use App\Apppackages;
use Log;

class Orders extends Model {

    protected $table = "orders";
    protected $appends = array('order_status_name', 'order_packages', 'order_profiles');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'order_id', 'order_price', 'promotion_price_applied', 'address_id', 'schedule_time', 'schedule_date', 'patient_id', 'order_status', 'payment_status', 'transaction_id', 'payment_id', 'token', 'packages', 'profiles', 'services', 'order_file', 'created_at', 'updated_at'];

    public function getCreatedAtAttribute($value) {
        return $this->attributes['created_at'] = date('d-M-Y h:i A', strtotime($value));
    }

    public function getUpdatedAtAttribute($value) {
        return $this->attributes['updated_at'] = date('d-M-Y h:i A', strtotime($value));
    }

    public function getPatientIdAttribute($value) {
        return $this->attributes['patient_id'] = json_decode($value, true);
    }

    public function getAddressIdAttribute($value) {
        return $this->attributes['address_id'] = json_decode($value, true);
    }

    public function getOrderFileAttribute($value) {
        if (isset($value)) {
            return $this->attributes['order_file'] = 'http://13.232.64.138/uploads/' . $value;
        } else {
            return $this->attributes['order_file'] = '';
        }
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

    public function getOrderPackagesAttribute() {

        $packs = NULL;
        $packages_data = $this->packages;
        $pack_data = json_decode($packages_data, true);
        if ($pack_data != NULL && is_array($pack_data)) {
            $pack_ids = array_column($pack_data, 'id');
            $packs = array();
            $packages = NULL;

            foreach ($pack_ids as $id) {
                $app_packages = Apppackages::where('package_id', $id)->first();
                if ($app_packages != NULL) {
                    $packages = $app_packages->package_name;
                }
                $packs[] = $packages;
            }
            //Log::info($packs);
            return $packs;
        } else {
            return $packs;
        }
    }

    public function getOrderProfilesAttribute() {

        $profs = NULL;
        $profiles_data = $this->profiles;
        $prof_data = json_decode($profiles_data, true);
        if ($prof_data != NULL && is_array($prof_data)) {
            $prof_ids = array_column($prof_data, 'id');
            $profs = array();
            $profiles = NULL;

            foreach ($prof_ids as $id) {
                $count = 0;
                $count = Services::where('id', $id)->count();
                if ($count > 0) {
                    $service = Services::where('id', $id)->first();
                    if ($service != NULL) {
                        $profiles = $service->investigations;
                    }
                } else {
                    $profile = Profiles::where('id', $id)->first();

                    if ($profile != NULL) {
                        $profiles = $profile->investigations;
                    }
                }
                $profs[] = $profiles;
            }
            //Log::info($profs);
            return $profs;
        } else {
            return $profs;
        }
    }

    public function reportfiles() {
        return $this->hasMany('App\Orderfiles', 'order_id', 'order_id');
    }

}
