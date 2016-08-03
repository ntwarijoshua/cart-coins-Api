<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\UserRewardRequest;
use App\Plan;
use App\Point;
use App\Reward;
use App\User;
use App\UserReward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class UserRewardsController extends Controller
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
            return JsonResponse::create(UserReward::with('user','reward')->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRewardRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRewardRequest $request)
    {
        $user = Auth::user();
        $company = Company::find($request['company_id']);
        $reward = Reward::find($request['reward_id']);
        $client = User::find($request['user_id']);
        $request->merge(['reward_id' => $reward->id, 'company_id' => $company->id, 'user_id' => $client->id ]);
        if($user->isShop() && $user->id == $company->manager_id){

            if($reward && $client){

                $client_point = Point::where('user_id', $client->id)->where('company_id', $company->id)->first();

                if($client_point->earned_point >= $reward->points){
                    $adjust = $client_point->earned_point - $reward->points;

                    $new_point =  Point::where('user_id',$client->id)->where('company_id',$company->id)->first();
                    $new_point->company_id = $company->id ;
                    $new_point->user_id = $client->id;
                    $new_point->earned_point = $adjust;
                    $new_point->save();

                    return JsonResponse::create(['message' => 'congratulation',
                        'success' => UserReward::create($request->all()) ],200);

            }else{
                    return JsonResponse::create(['error' => 'few_point', 'error' => 'bad_request'],400);
                }

            }else{
                return JsonResponse::create(['error' => 'bad_request'],400);
            }
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
            return JsonResponse::create(UserReward::with('user','reward')->where('id', $id)->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }
}
