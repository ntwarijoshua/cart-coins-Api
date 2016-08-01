<?php

namespace App\Http\Controllers;

use App\Company;
use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostsController extends Controller
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
            return JsonResponse::create(Post::with('company')->get());
        }
        else{
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
        $company = Company::findOrFail($request->input('company_id'));
        if($user->isShop() && $company->manager_id == $user->id){
            $file = Input::file('file');
            if(isset($file)){
                $request->merge(['attached_file' => $file, 'company_id' => $company->id ]);
                $request->all();
                $post = Post::create($request->all());
                if($post){
                    $this->uploadFile($post->id,$file);
                }
            }
            else{
                $request->merge(['company_id' => $company->id ]);
                $request->all();
                $post = Post::create($request->all());
            }
            return JsonResponse::create($post);
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }

    public function uploadFile($post_id, $file){
        $post = Post::find($post_id);

        $file = Input::file('file')->getClientOriginalName();

        $extension = Input::file('file')->getClientOriginalExtension();

        $filename = $post_id. '.' . $extension;

        $destination_path =  'uploads/posts/';
        $post->attached_file = $destination_path.$filename;


        Input::file('file')->move($destination_path, $filename);

        $post->save();
        return JsonResponse::create('Post_uploaded');
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
            return JsonResponse::create(Post::with('company')->where('id', $id)->get());
        }
        else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
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
        $post = Post::findOrFail($id);
        $company = Company::findOrfail($post->company_id);
        if($user->isShop() && $user->id == $company->manager_id){
            $data = $request->all();
            $post->update($data);
            return JsonResponse::create($post);
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
        $post = Post::findOrFail($id);
        $company = Company::findOrfail($post->company_id);
        if($user->isShop() && $user->id == $company->manager_id){
            $post->delete();
            return JsonResponse::create(['message' => 'deleted'],200);
        }else{
            return JsonResponse::create(['error' => 'not_allowed'],401);
        }
    }
}
