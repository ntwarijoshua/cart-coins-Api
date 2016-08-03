<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\SharePostRequest;
use App\Plan;
use App\Point;
use App\Post;
use App\SharedPost;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class SharedPostsController extends Controller
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
            return JsonResponse::create(SharedPost::with('user','post')->where('user_id', $user->id)->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SharePostRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SharePostRequest $request)
    {
        $user = Auth::user();
        if($user){
            $post_exist = Post::findOrFail($request->input('post_id'));
            $request->merge(['post_id' => $post_exist->id, 'user_id' => $user->id]);
            $share = SharedPost::create($request->all());
            if($share AND $post_exist){
                return $this->UpdatePoint($share);
            }else{
                return JsonResponse::create(['error' => 'many_request'],429);
            }
            //return JsonResponse::create($share);
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    private function UpdatePoint($share){
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);
        $company = Post::findOrfail($share->post_id);
        $plan = Plan::where('company_id', $company->company_id)->first();

        if(empty($plan)){
            return JsonResponse::create(['error' => 'bad_request'],400);
        }

        $relation = $user->company->first();

        if(!empty($relation)){
            $the_relation = Point::where('user_id',$relation->pivot['user_id'])
                                 ->where('company_id', $company->company_id )
                                 ->first();

            if(!empty($the_relation)) {

                $adder = $the_relation->earned_point + $plan->points;
                $the_relation->company_id = $company->company_id;
                $the_relation->user_id = $user->id;
                $the_relation->earned_point = $adder;
                $the_relation->save();

                $user->userPoint()->attach($company->company_id, [
                    'user_id' => $user->id,
                    'points' => $plan->points,
                    'point_date' => date('Y-m-d'),
                    'post'  => $company->id
                ]);
                  return JsonResponse::create($user);


            }else{
                //return JsonResponse::create("no relation with comp");
                $new_point =  Point::where('user_id',$user->id)->where('company_id',$company->company_id)->first();
                $new_point->company_id = $company->company_id;
                $new_point->user_id = $user->id;
                $new_point->earned_point = $plan->points;
                $new_point->save();

                $user->userPoint()->attach($company->company_id, [
                    'user_id' => $user->id,
                    'points' => $plan->points,
                    'point_date' => date('y-m-d'),
                    'post'  => $company->id
                ]);
                return JsonResponse::create($user);
            }

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
            return JsonResponse::create(SharedPost::with('user','post')
                ->where('user_id', $user->id)
                ->where('id', $id)
                ->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }
}
