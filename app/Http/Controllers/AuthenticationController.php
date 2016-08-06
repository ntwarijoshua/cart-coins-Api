<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function facebook(Request $request){

        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VybmFtZSI6IlJveSIsInJvbGUiOiJhY2NvdW50Lm1hbmFnZXIiLCJzdWIiOjUyLCJpc3MiOiJodHRwOlwvXC90ZXN0LmR1bGwtYXBpLmFwcHJlY2lhdGUuYmVcL2FwaVwvdjFcL2F1dGhlbnRpY2F0ZSIsImlhdCI6MTQ3MDM5MDEzNSwiZXhwIjoxNDcwMzkzNzM1LCJuYmYiOjE0NzAzOTAxMzUsImp0aSI6IjBiN2IyOTZkOGRmMzY5MmIwMGRlNjM1YjMzZjcwOTY2In0.AvTrRSlmXkdiEweAx5sLuzifp2ivrOtyiABsYiieTso';
        return response()->json(compact($token))
    }
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }




}
