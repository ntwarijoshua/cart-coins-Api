<?php

namespace App\Http\Middleware;

use App\Company;
use App\Subscription;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class SubscriptionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $today = date("Y-m-d");
        if(($user->isAdmin())){
            return $next($request);
        }
        else {
            $user_exist = Company::where('manager_id', $user->id)->first();
            $sub = Subscription::where('company_id', $user_exist->id)->first();

            if (  (!empty($sub->end_date) && $sub->end_date < $today) || empty($sub->end_date) ) {
                $sub->status = 'deactive';
                $sub->save();
                return JsonResponse::create(['error' => 'subscription_expired'],401);
            }

            else
                $sub->status = 'active';
                $sub->save();
            return $next($request);
        }
    }
}
