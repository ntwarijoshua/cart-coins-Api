<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\SubscriptionRequest;
use App\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $company = Company::first();
        if($user->isAdmin()){
            return JsonResponse::create(Subscription::with('subscribe','company')->get());
        }
        elseif($user->id == $company->manager_id){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('company_id',$company->id)->get());
        }
        else
            return JsonResponse::create(['error' => 'not_allowed'],401);
    }

    public function active(){
        $user = Auth::user();
        $company = Company::first();
        if($user->isAdmin()){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('status', "active")->get());
        }
        elseif($user->id == $company->manager_id){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('company_id',$company->id)->where('status', "active")->get());
        }
        else
            return JsonResponse::create(['error' => 'not_allowed'],401);
    }

    public function deActive(){
        $user = Auth::user();
        $company = Company::first();
        if($user->isAdmin()){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('status', "deactive")->get());
        }
        elseif($user->id == $company->manager_id){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('company_id',$company->id)->where('status', "deactive")->get());
        }
        else
            return JsonResponse::create(['error' => 'not_allowed'],401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriptionRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionRequest $request)
    {
        $user = Auth::user();
        $owner = Company::findOrFail($request->input('company_id'));
        if(empty($owner)){
            return JsonResponse::create('bad_request');
        }

        $request->merge(['user_id'=>$user->id, 'company_id' => $owner->id ]);
        $data = $request->all();
        if($user->isadmin()) {
            return ($this->create_subscription($data));
        }
        else{
            return JsonResponse::create("access_denied");
        }
    }

    public function create_subscription($data){

        $exist = Subscription::where('company_id', $data['company_id'])->first();

        if(empty($exist)) {
            $subscribe = Subscription::create($data);
            $subscribe->subscribe()->attach($subscribe->id,
                ['company_id' => $data['company_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'price' => $data['price'],
                'status' => $data['status']]);
            return JsonResponse::create($subscribe);
        }
        else{
            $exist->update($data);
            $exist->subscribe()->attach($exist->id,
                ['company_id' => $data['company_id'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'price' => $data['price'],
                    'status' => $data['status']]);
            return JsonResponse::create($exist);
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
        $company = Company::first();
        if($user->isAdmin()){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('id', $id)->get());
        }
        elseif($user->id == $company->manager_id){
            return JsonResponse::create(Subscription::with('subscribe','company')->where('company_id',$company->id)->where('id', $id)->get());
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
        if($user->isAdmin()){
            $exist = Subscription::findOrFail($id);
            $exist->delete();
            return JsonResponse::create(['message' => 'deleted'],200);
        }
        else
            return JsonResponse::create(['error' => 'not_allowed'],401);
    }
}
