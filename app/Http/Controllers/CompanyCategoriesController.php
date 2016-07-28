<?php

namespace App\Http\Controllers;

use App\CompanyCategory;
use App\Http\Requests\CompanyCategoryRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user){
            return JsonResponse::create(CompanyCategory::all());
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyCategoryRequest $request)
    {
        $user = Auth::user();
        if($user->isAdmin()){
            return JsonResponse::create(CompanyCategory::create($request->all()));
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if($user){
            return JsonResponse::create(CompanyCategory::findOrFail($id));
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if($user->isAdmin()){
            $data = Input::all();
            $exist = CompanyCategory::findOrFail($id);
            $exist->update($data);
            return JsonResponse::create($exist);
        }
        else{
            return JsonResponse::create(['error' => 'not_allowod'],401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        if($user->isAdmin()){
            $exists = CompanyCategory::findOrFail($id);
            $exists->delete();
            return JsonResponse::create(['message' => 'deleted'],200);
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }
}
