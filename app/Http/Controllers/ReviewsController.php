<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\ReviewsRequest;
use App\Review;
use App\Star;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
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
            return JsonResponse::create(Review::with('company','user','star')->where('user_id',$user->id)->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ReviewsRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewsRequest $request)
    {
        $user = Auth::user();

        $star = Star::find($request->input('star_id'));
        $company = Company::find($request->input('company_id'));
        $review_exist = Review::where('user_id',$user->id)->where('company_id',$company->id)->first();
        if($review_exist){
            return JsonResponse::create(['error' => 'already_exist'],409);
        }
        $request->merge(['star_id' => $star->id, 'company_id' => $company->id, 'user_id' => $user->id]);
        return JsonResponse::create(Review::create($request->all()));
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
            return JsonResponse::create(Review::with('company','user','star')->where('user_id',$user->id)->where('id',$id)->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

}
