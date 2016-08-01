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
// Generate a login URL
Route::get('/facebook/login', function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb)
{
    // Send an array of permissions to request
    $login_url = $fb->getLoginUrl(['email']);

    // Obviously you'd do this in blade :)
    echo '<a href="' . $login_url . '">Login with Facebook</a>';
});
// Endpoint that is redirected to after an authentication attempt
Route::get('/facebook/callback/{code}', 'AuthenticationController@VerifyFacebook');
//    function(SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb)
//{
    // Obtain an access token.
//    try {
//        $token = $fb->getAccessTokenFromRedirect();
//    } catch (Facebook\Exceptions\FacebookSDKException $e) {
//        dd($e->getMessage());
//    }
//
//    // Access token will be null if the user denied the request
//    // or if someone just hit this URL outside of the OAuth flow.
//    if (! $token) {
//        // Get the redirect helper
//        $helper = $fb->getRedirectLoginHelper();
//
//        if (! $helper->getError()) {
//            abort(403, 'Unauthorized action.');
//        }
//
//        // User denied the request
//        dd(
//            $helper->getError(),
//            $helper->getErrorCode(),
//            $helper->getErrorReason(),
//            $helper->getErrorDescription()
//        );
//    }
//
//    if (! $token->isLongLived()) {
//        // OAuth 2.0 client handler
//        $oauth_client = $fb->getOAuth2Client();
//
//        // Extend the access token.
//        try {
//            $token = $oauth_client->getLongLivedAccessToken($token);
//        } catch (Facebook\Exceptions\FacebookSDKException $e) {
//            dd($e->getMessage());
//        }
//    }
//
//    $fb->setDefaultAccessToken($token);
//
//    // Save for later
//    Session::put('fb_user_access_token', (string) $token);
//
//    // Get basic info on the user from Facebook.
//    try {
//        $response = $fb->get('/me?fields=id,name,email');
//    } catch (Facebook\Exceptions\FacebookSDKException $e) {
//        dd($e->getMessage());
//    }
//
//    // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
//    $facebook_user = $response->getGraphUser();
//
//    // Create the user if it does not exist or update the existing entry.
//    // This will only work if you've added the SyncableGraphNodeTrait to your User model.
//    $user = App\User::createOrUpdateGraphNode($facebook_user);
//
//    // Log the user into Laravel
//    Auth::login($user);
//
//    return redirect('/')->with('message', 'Successfully logged in with Facebook');
//});


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

