<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\PlanRequest;
use App\Plan;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlansController extends Controller
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
            return JsonResponse::create(Plan::with('company')->get());
        }else
            return JsonResponse::create(['error' => 'not_allowed'],401);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PlanRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {
        $user = Auth::user();
        $company_exist = Company::findOrFail($request->input('company_id'));
        if($user->isAdmin()){
            $hasPointPlan = Plan::where('company_id',$company_exist->id)->first();
            if(empty($hasPointPlan)){
                $pointPlan = [
                    'company_id'    => $company_exist->id,
                    'points'        => $request['points'],
                    'equivalent'    => $request['equivalent']
                ];
                $savePlan = Plan::create($pointPlan);
                return JsonResponse::create($savePlan);
            }else{
                $pointPlan = ['points'=>$request['points'], 'equivalent' => $request['equivalent'], 'company_id' => $company_exist->id];
                $hasPointPlan->update($pointPlan);
                return JsonResponse::create($hasPointPlan);
            }
        }elseif ($user->isShop()){
            if(empty($hasPointPlan)){
                $pointPlan = [
                    'company_id'    => $company_exist->id,
                    'points'        => $request['points'],
                    'equivalent'    => $request['equivalent']
                ];
                $savePlan = Plan::create($pointPlan);
                return JsonResponse::create($savePlan);
            }else{
                $pointPlan = ['points'=>$request['points'], 'equivalent' => $request['equivalent'], 'company_id' => $company_exist->id];
                $hasPointPlan->update($pointPlan);
                return JsonResponse::create($hasPointPlan);
            }
        }else
            return JsonResponse::create(['error' => 'not_allowed'],401);

    }

}
