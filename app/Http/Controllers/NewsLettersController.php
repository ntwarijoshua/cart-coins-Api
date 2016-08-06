<?php

namespace App\Http\Controllers;

use App\Newsletter;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class NewsLettersController extends Controller
{
    public function store(){
        $user = Auth::user();
        if($user->isShop() || $user->isAdmin()){

            Newsletter::create(Input::all());
            $receiver = User::find(7);

            Mail::send([],[],function($message)use($receiver){
                $message->to($receiver->email);
                $message->subject('cart coin');
            });

        }
    }
}
