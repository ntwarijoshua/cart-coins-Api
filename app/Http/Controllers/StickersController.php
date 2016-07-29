<?php

namespace App\Http\Controllers;

use App\Sticker;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\JsonResponse;

class StickersController extends Controller
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
            return JsonResponse::create(Sticker::with('user')->get());
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
            $photo = Input::file('photo');
                $payable  = $request['payable'];
                if(isset($payable) == 1){
                $price = $request['price'];
                $owner = $request['user_id'];

                $request->merge(['details' => $photo, 'price' => $price, 'user_id' => $owner]);
                $request->all();
                $sticker = Sticker::create($request->all());
                if($sticker){
                    $this->uploadPhoto($sticker->id,$photo);
                }
                }else{
                    $request->merge(['details' => $photo]);
                    $request->all();
                    $sticker = Sticker::create($request->all());
                    if($sticker){
                        $this->uploadPhoto($sticker->id,$photo);
                    }
                }
            return JsonResponse::create($sticker);
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    public function uploadPhoto($sticker_id, $photo){
        $sticker  = Sticker::findOrFail($sticker_id);
        if ($photo) {


            $photo = Input::file('photo')->getClientOriginalName();

            $extension = Input::file('photo')->getClientOriginalExtension();

            $filename = $sticker_id. '.' . $extension;

            $destination_path =  'uploads/stickers/';
            $sticker->details = $destination_path.$filename;


            Input::file('photo')->move($destination_path, $filename);

            $sticker->save();
            return JsonResponse::create(['success' => 'sticker_uploaded'],200);

        } else {
            return JsonResponse::create(['error' => 'upload failed'], 400);

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
            return JsonResponse::create(Sticker::with('user')->where('id',$id)->get());
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
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
        $exist = Sticker::findOrFail($id);
        if($user->isAdmin() || $user->id == $exist->user_id ){
            $exist->delete();
            return JsonResponse::create(['success' => 'deleted'],200);
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }
}
