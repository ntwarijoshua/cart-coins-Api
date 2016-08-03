<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Point;
use App\Post;
use App\SharedPost;
use App\SubShared;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class SubSharedController extends Controller
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
            return JsonResponse::create(SubShared::with('user', 'shared')->where('user_id',$user->id)->get());
        }elseif($user->isAdmin()){
            return JsonResponse::create(SubShared::with('user', 'shared')->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if($user){
            $share_exist = SharedPost::findOrFail($request->input('shared_post_id'));

            $request->merge(['shared_post_id' => $share_exist->id,
                'user_id' => $user->id,
                'date' => date('Y-m-d')]);
            if(!empty($share_exist)){

                $create = SubShared::create($request->all());
                if($create){

                    $this->UpdateExistingUserPoint($share_exist);
                    return $this->AdjustPoint($create,$share_exist);

                }else{
                    return JsonResponse::create(['error' => 'many_request'],429);
                }
            }
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    private function UpdateExistingUserPoint($share_exist){

        $company = Post::findOrFail($share_exist->post_id);

        $the_relation = Point::where('user_id',$share_exist->user_id)
            ->where('company_id', $company->company_id )
            ->first();
        $plan = Plan::where('company_id', $company->company_id)->first();
        if(empty($plan)){
            return JsonResponse::create(['error' => 'bad_request'],400);
        }

        if(!empty($the_relation)) {

            $adder = $the_relation->earned_point + $plan->points;
            $the_relation->company_id = $company->company_id;
            $the_relation->user_id = $the_relation->user_id;
            $the_relation->earned_point = $adder;
            $the_relation->save();
            $user_point = User::findOrFail($the_relation->user_id);
            $user_point->userPoint()->attach($company->company_id, [
                'user_id' => $the_relation->user_id,
                'points' => $plan->points,
                'point_date' => date('Y-m-d'),
                'post'  => $company->id
            ]);
            return JsonResponse::create($user_point);
        }
        else{
            return JsonResponse::create(['error' => 'bad_request'],400);
        }

    }

    private function AdjustPoint($create,$share_exist){
        $user = Auth::user();
        $company = Post::findOrFail($share_exist->post_id);

//        $the_relation = Point::where('user_id',$share_exist->user_id)
//            ->where('company_id', $company->company_id )
//            ->first();

        $plan = Plan::where('company_id', $company->company_id)->first();
        if(empty($plan)){
            return JsonResponse::create(['error' => 'bad_request'],400);
        }
        $relation = $user->company->first();

        if(!empty($relation)){
            return JsonResponse::create(['error' => 'user_exist'],409);
        }
        else{

            $user->company()->attach($user->id, ['company_id' => $company->company_id, 'earned_point' =>$plan->points ]);
            $user->userPoint()->attach($company->company_id, [
                'user_id' => $user->id,
                'points' => $plan->points,
                'point_date' => date('y-m-d'),
                'post'  => $company->id
            ]);
            return JsonResponse::create($user);

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
            return JsonResponse::create(SubShared::with('user', 'shared')
                ->where('user_id',$user->id)->where('id',$id)->get());
        }elseif($user->isAdmin()){
            return JsonResponse::create(SubShared::with('user', 'shared')->where('id',$id)->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }
}
