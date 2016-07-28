<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\CompanyRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->isAdmin()){
            return JsonResponse::create(Company::with('user','manager')->get());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CompanyRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $user = Auth::user();

        if($user->isAdmin()){
            $manager_exist = User::findOrFail($request->input('manager_id'));
            $request->merge(['user_id' => $user->id, 'manager_id' => $manager_exist->id]);
            return JsonResponse::create(Company::create($request->all()));
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
        if($user->isAdmin()){
            return JsonResponse::create(Company::with('user','manager')->where('id',$id)->get());
        }
        else
            return JsonResponse::create(['error' => 'not_allowed'],401);
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
        if($user->is('admin')){
            $company_exist = Company::findOrFail($id);
            $data = $request->all();
            $company_exist->update($data);
            return JsonResponse::create($company_exist);
        }
        else
            return JsonResponse::create(['error' => 'not_allowed'],401);
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
        if($user->is('admin')){
            $company_exist = Company::findOrFail($id);
            $company_exist->delete();
            return JsonResponse::create(['error' => 'deleted'],200);
        }
        else{
            return JsonResponse::create(['error' => 'deleted'],200);
        }
    }
}
