<?php

namespace App\Http\Middleware;

use App\Jobs\SendNotificationOnUserForExpiredApplication;
use App\Models\Application;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckExpiredApplication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        //Get all expired application
        $expiredApplications = Application::expiredApplication()->where('expiration_notified', false)->get();
        if (empty($expiredApplications)) {
            return $next($request);
        }

        foreach ($expiredApplications as $application) {
            $restrictedUsers = User::restrictedUser()->doesntHave('permissions', 'or')->pluck('id');
            $users = User::whereNotIn('id', $restrictedUsers)->get();
            $users->each(function ($user, $key) use ($application) {
                SendNotificationOnUserForExpiredApplication::dispatch($user, $application);
            });
            SendNotificationOnUserForExpiredApplication::dispatch($application->user, $application);
        }

        return $next($request);
    }
}
