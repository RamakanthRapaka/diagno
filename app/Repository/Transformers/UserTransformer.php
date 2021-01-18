<?php
/**
 * Created by PhpStorm.
 * User: d.adelekan
 * Date: 24/08/2016
 * Time: 01:57
 */

namespace App\Repository\Transformers;
use App\Wallet;

class UserTransformer extends Transformer{

    public function transform($user){
        
        $promotional_balance = 'empty';
        $name = 'empty';
        $email = 'empty';
        $dob = 'empty';
        $gender = 'empty';
        $blood_group = 'empty';
        $wallet = Wallet::where('id',$user->id)->first();
        if(isset($wallet) && isset($wallet->promotional_balance))    
        {
        $promotional_balance = $wallet->promotional_balance;    
        }
        
        if(isset($user) && isset($user->email))    
        {
        $email = $user->email;    
        }
        
        if(isset($user) && isset($user->name))    
        {
        $name = $user->name;    
        }
        
        if(isset($user) && isset($user->dob))    
        {
        $dob = $user->dob;    
        }
        
        if(isset($user) && isset($user->gender))    
        {
        $gender = $user->gender;    
        }
        
        if(isset($user) && isset($user->blood_group))    
        {
        $blood_group = $user->blood_group;    
        }
        
        
        return [
            'name' => $name,
            'email' => $email,
            'mobile' => $user->mobile,
            'dob' => $dob,
            'gender' => $gender,
            'blood_group' => $blood_group,
            'promotional_balance' => $promotional_balance,
            'api_token' => $user->api_token,
        ];

    }

}