<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsersController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        if($user->is('admin')){
            return JsonResponse::create(User::all());
        }
        elseif($user){
            return JsonResponse::create(User::findOrFail($user->id));
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }


    public function register(UsersRequest $request){
        $request->merge(['password' => Hash::make($request->input('password')), 'keyword' => dechex( mt_rand(1000000, 9999999) )]);
        $data = $request->all();
        return JsonResponse::create(User::create($data));
    }


    public function store(UsersRequest $request)
    {
        $user = Auth::user();
        if($user->is('admin')){
            $request->merge(['password' => Hash::make($request->input('password'))]);
            $data = $request->all();
            return JsonResponse::create(User::create($data));
        }
        else{
            return JsonResponse::create(['not_allowed'],401);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        if($user->isAdmin()) {
            return JsonResponse::create(User::findOrFail($id));
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if($user->id == $id || $user->is('admin')){
            $find_user = User::findOrFail($id);
            $data = $request->all();
            $find_user->update($data);
            return JsonResponse::create($find_user);
        }
        else{
            return JsonResponse::create(['not_allowed'],401);
        }
    }


    public function change_password(Request $request, $id)
    {
        $user = Auth::user();
        if($user->id == $id || $user->is('admin')){
            $find_user = User::findOrFail($id);
            $request->merge(['password' => Hash::make($request->input('password'))]);
            $data = $request->all();
            $find_user->update($data);
            return JsonResponse::create($find_user);
        }
        else{
            return JsonResponse::create(['not_allowed'],401);
        }
    }


    public function destroy($id)
    {
        $user = Auth::user();
        $find_user = User::findOrFail($id);
        if($user->is('admin')){
            $find_user->delete();
            return JsonResponse::create(['deleted'],200);
        }
    }
}
