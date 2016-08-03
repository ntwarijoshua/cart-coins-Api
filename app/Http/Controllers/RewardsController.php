<?php

namespace App\Http\Controllers;

use App\Company;
use App\Http\Requests\RewardRequest;
use App\Reward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class RewardsController extends Controller
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
            return JsonResponse::create(Reward::with('company')->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RewardRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RewardRequest $request)
    {
        $user = Auth::user();

            $company_exist = Company::findOrFail($request->input('company_id'));
            $photo = Input::file('photo');

            if($company_exist->manager_id == $user->id && $user->isShop()){
                if(isset($photo)){
                    $request->merge(['company_id' => $company_exist->id, 'photo' => $photo]);
                    $reward = Reward::create($request->all());
                    if($reward){
                        return $this->UploadPhoto($reward);
                    }else{
                        return JsonResponse::create(['error' => 'many_request'],429);
                    }
                }else{
                    $request->merge(['company_id' => $company_exist->id]);
                    return JsonResponse::create(Reward::create($request->all()));
                }
            }
            else{
                return JsonResponse::create(['not_allowed'],401);
            }
    }

    public function UploadPhoto($reward){
       // $photo = Input::file('photo')->getClientOriginalName();

        $extension = Input::file('photo')->getClientOriginalExtension();

        $filename = $reward->id. '.' . $extension;

        $destination_path =  'uploads/rewards/';
        $reward->photo = $destination_path.$filename;


        Input::file('photo')->move($destination_path, $filename);

        $reward->save();
        if($reward) {
            return JsonResponse::create(['success' => 'reward_file_uploaded', 'reward' => $reward], 200);
        }
        else{
            return JsonResponse::create(['error' => 'many_request'],429);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
