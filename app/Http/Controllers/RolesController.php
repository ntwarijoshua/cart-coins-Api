<?php

namespace App\Http\Controllers;
use App\Role;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class RolesController extends Controller
{
    public function index(){
        $user = Auth::user();
        if($user->is('admin')) {
            return JsonResponse::create(Role::all());
        }
        else{
            return JsonResponse::create(['not_allowed'],401);
    }
    }
}
