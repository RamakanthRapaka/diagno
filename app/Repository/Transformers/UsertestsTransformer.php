<?php
/**
 * Created by PhpStorm.
 * User: d.adelekan
 * Date: 24/08/2016
 * Time: 01:57
 */

namespace App\Repository\Transformers;


class UsertestsTransformer extends Transformer{

    public function transform($usertests){

        return [
            'id' => $usertests->id,
            'user_id' => $usertests->user_id,
            'doc_path' => $usertests->doc_path,
            'created_at' => $usertests->created_at,
        ];

    }

}