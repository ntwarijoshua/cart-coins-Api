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
        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'redirect_uri' => $request->input('redirectUri'),
            'client_secret' => Config::get('app.facebook_secret')
        ];
        //Exchange Authorization code for Access Token.
        $facebookAccessTokenResponse = $client->request('GET','https://graph.facebook.com/v2.5/oauth/access_token',[
            'query'=>$params
        ]);

        $facebookAccessToken = json_decode($facebookAccessTokenResponse->getBody(),true);
        //return response()->json($facebookAccessToken['access_token']);
        //Get Profile From Facebook.
        $fields = 'id,email,first_name,last_name,name';
        $facebookProfileResponse = $client->request('GET','https://graph.facebook.com/v2.5/me',[
            'query'=>[
                'access_token'=>$facebookAccessToken['access_token'],
                'field'=>$fields
            ]
        ]);

        $facebookProfile = json_decode($facebookProfileResponse->getBody(),true);

        //If User is Authenticated
        if($request->header('Authorization')){
            $user = User::where('facebook_id',$facebookProfile['id']);
            if($user->first()){
                return response()->json(['message'=>'There is already a facebook account that belongs to you!'],409);
            }

            $user = Auth::user();
            $user->facebook_id = $facebookProfile['id'];
            $user->email = $user->email ?: $facebookProfile['email'];
            $user->display_name = $facebookProfile['name'];
            $user->save();

            return response()->json(['token'=>$this->createToken($user)]);
        }else{
            //If user is not authenticated.
            $user = User::where('facebook_id','=',$facebookProfile['id']);
            if($user->first()){
                return response()->json(['token'=>$this->createToken($user)]);
            }

            $user = new User();
            $user->facebook_id = $facebookProfile['id'];
            $user->email = $facebookProfile['email'];
            $user->first_name = $facebookProfile['first_name'];
            $user->last_name = $facebookProfile['last_name'];
            $user->display_name = $facebookProfile['name'];
            $user->save();

            return response()->json(['token'=>$this->createToken($user)]);
        }
    }





}
