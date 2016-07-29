<?php

namespace App\Http\Controllers;

use App\Company;
use App\ShopSticker;
use App\Sticker;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShopStickersController extends Controller
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
        if($user->isAdmin()) {
            return JsonResponse::create(ShopSticker::with('sticker', 'company')->get());
        }elseif ($user->isShop() && $user->id == $company->manager_id){
            return JsonResponse::create(ShopSticker::with('sticker', 'company')->where('company_id', $company->id)->get());
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
        if($user->isShop()){
            $sticker_exist = Sticker::findOrFail($request->input('sticker_id'));
            $company_exist = Company::findOrFail($request->input('company_id'));
            $request->merge(['sticker_id' => $sticker_exist->id, 'company_id' => $company_exist->id ]);
            $pathToFile = $sticker_exist->details;
            $sticker = ShopSticker::create($request->all());
            if($sticker){

                if($sticker_exist->payable == false) {
                    return response()->download($pathToFile);
                }else{
                    return JsonResponse::create(['success' => 'online_payment_coming_soon'],200);
                }

            }else{
                return JsonResponse::create(['error' => 'bad_request'],400);
            }
            return JsonResponse::create($sticker);
        }else{
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
        $company = Company::first();
        if($user->isAdmin()) {
            return JsonResponse::create(ShopSticker::with('sticker', 'company')->where('id', $id)->get());
        }elseif ($user->isShop() && $user->id == $company->manager_id){
            return JsonResponse::create(ShopSticker::with('sticker', 'company')->where('company_id', $company->id)->where('id',$id)->get());
        }
    }

}
