<?php

namespace App\Http\Controllers;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function  createToken($user){

        return JWTAuth::fromUser($user);
    }

    public function authenticate(Request $request){
        $email = $request->get("email");
        $password = $request->get("password");

        $user = User::where('email','=',$email)->first();
        if(!$user){
            return JsonResponse::create(['error','Wrong email and/or password'],401);
        }

        if(Hash::check($password,$user->password)){

            unset($user->password);
            return response()->json(['token'=>$this->createToken($user)]);
        }else{
            return JsonResponse::create(['error','Wrong email and/or password'],401);
        }
    }

    public function facebookAuth(Request $request){
        $client = new Client();
    }





}
