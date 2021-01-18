<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\Transformers\UserTransformer;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mailtrap;


class MailController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\UserTransformer
     * */
    protected $userTransformer;

    public function __construct(userTransformer $userTransformer) {

        $this->userTransformer = $userTransformer;
    }
    
    
    public function login($user) {

        if (isset($user->email)) {
             $email = $user->email;
        }

        if (isset($user->subject)) {
            $subject = $user->subject;
        }

        Mail::send('mail', ['maildata' => $user], function ($message) use($email, $subject) {

            $message->subject($subject);
            $message->from('ramakanth.rapaka@gmail.com', 'CallLabs');

            $message->to($email);
        });
    }
}
