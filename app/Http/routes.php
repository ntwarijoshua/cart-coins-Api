<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api/v1'], function () {
    Route::post('register', 'UsersController@register');
    Route::post('login','AuthenticationController@authenticate');

    #Need token to access those router
    Route::group(['middleware'=>['jwt.auth']], function(){

        Route::group(['middleware' => ['subscribed']], function(){
        //Get all roles
            Route::get('roles', 'RolesController@index');
            //Users router
            Route::resource('users', 'UsersController');
            Route::put('change-password/{id}','UsersController@change_password');

            //Company category router
            Route::resource('company-categories', 'CompanyCategoriesController');
            //Companies router
            Route::resource('companies','CompaniesController');
            //Subscription route
            Route::resource('subscribe','SubscriptionsController');
            //active company
            Route::get('active', 'SubscriptionsController@active');
            Route::get('deactive', 'SubscriptionsController@deActive');
            //Points plan route
            Route::resource('plan', 'PlansController');
            //Sticker route
            Route::resource('stickers', 'StickersController');
            //Shop sticker route
            Route::resource('shop-sticker', 'ShopStickersController');
            //Post router
            Route::resource('posts', 'PostsController');
        });
    });
});

