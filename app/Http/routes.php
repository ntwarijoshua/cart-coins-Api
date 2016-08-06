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

Route::group(['middleware' => 'cors'], function(){
   // $router->get('api', 'ApiController@index');

Route::get('/', function () {
    return view('welcome');
});
// Generate a login URL
//Route::get('/facebook/login', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb)
//{
//    // Send an array of permissions to request
//    $login_url = $fb->getLoginUrl(['email']);
//
//    // Obviously you'd do this in blade :)
//    echo '<a href="' . $login_url . '">Login with Facebook</a>';
//});

Route::get('/facebook/login', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {
    $login_link = $fb
        ->getRedirectLoginHelper()
        ->getLoginUrl('https://cartcoins.com/facebook/callback', ['email', 'user_events']);

    echo '<a href="' . $login_link . '">Log in with Facebook</a>';
});


Route::get('/facebook/callback', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb) {
    try {
        $token = $fb
            ->getRedirectLoginHelper()
            ->getAccessToken();
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // Failed to obtain access token
        dd($e->getMessage());
    }

    if (!$token) {
        // User denied the request
        exit("not allowed");
    }else{
        exit("allowed");
    }
});





Route::group(['prefix' => 'api/v1','middleware'=>['api']], function () {
    Route::post('auth/facebook','AuthenticationController@facebookAuth');
    Route::post('register', 'UsersController@register');
    Route::post('login','AuthenticationController@authenticate');


    #Need token to access those routes
    Route::group(['middleware'=>['jwt.auth']], function(){
        //reward route
        Route::resource('rewards', 'RewardsController', ['only' => ['index', 'show']]);

        //company category reward
        Route::resource('company-categories', 'CompanyCategoriesController', ['only' => ['index','show']]);

        //Points plan route
        Route::resource('plan', 'PlansController', ['only' => ['index', 'show']]);

        //Sticker route
        Route::resource('stickers', 'StickersController', ['only' => ['index', 'store', 'show', 'destroy']]);

        //Post router
        Route::resource('posts', 'PostsController', ['only' => ['index', 'show']]);

        //Share post route
        Route::resource('shared-post', 'SharedPostsController', ['only' => ['index', 'show','store']]);

        //Sub share post route
        Route::resource('sub-share', 'SubSharedController', ['only' => ['index', 'store', 'show', 'destroy']]);

        //reviews controller
        Route::resource('reviews','ReviewsController');

        //Need subscription to access those routes
        Route::group(['middleware' => ['subscribed']], function(){
        //Get all roles
            Route::get('roles', 'RolesController@index');
            //Users route
            Route::resource('users', 'UsersController');
            Route::put('change-password/{id}','UsersController@change_password');

            //Company category route
            Route::resource('company-categories', 'CompanyCategoriesController', ['except' => ['index','show']]);
            //Companies route
            Route::resource('companies','CompaniesController');
            //Subscription route
            Route::resource('subscribe','SubscriptionsController');
            //active company
            Route::get('active', 'SubscriptionsController@active');
            Route::get('deactive', 'SubscriptionsController@deActive');
            //Points plan route
            Route::resource('plan', 'PlansController', ['except' => ['index', 'show']]);
            //Sticker route
            Route::resource('stickers', 'StickersController', ['except' => ['index', 'store', 'show', 'destroy']]);
            //Shop sticker route
            Route::resource('shop-sticker', 'ShopStickersController');
            //Post router
            Route::resource('posts', 'PostsController', ['except' => ['index', 'show']]);
            //Share post route
            Route::resource('shared-post', 'SharedPostsController', ['except' => ['index', 'show','store']]);
            //Sub share post route
            Route::resource('sub-share', 'SubSharedController', ['except' => ['index', 'store', 'show', 'destroy']]);
            //Rewards route
            Route::resource('rewards', 'RewardsController', ['only' => ['store', 'update', 'destroy']]);

            //User reward route
            Route::resource('user-reward', 'UserRewardsController');

            //News letter
            Route::resource('newsletter', 'NewsLettersController');


        });
    });
});

});