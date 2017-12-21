<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AccessLog;
use App\Models\User;

trait LoginSuspensionTrait
{
    /**
     * Get the failed login attempts for the username
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $days
     * @return integer
     */
    public function failedAttempts(Request $request, $days)
    {
        return AccessLog::where('user_name', $request->user)
        ->where('created_at', '>', Carbon::now()->subDays($days))
        ->count();
    }

    /**
     * Check if the user has reached the limit of failed login attempts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function reachedFailedAttemptsLimit(Request $request)
    {
        if($this->failedAttempts($request, 1) >= 5 ||
            $this->failedAttempts($request, 7) >= 7 ||
            $this->failedAttempts($request, 30) >= 20
        ) {
            return true;

        }
        return false;
    }

    /**
     * Suspend the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function userSuspension(Request $request)
    {
        $user = User::where('user', $request->user)
        ->first();

        $user->status = -1;
        $user->save();
    }

    /**
     * Send the response when the user has no access to the site.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendForbiddenResponse(Request $request)
    {
        switch($this->guard()->user()->status) {
            case -1:
                $status = 'suspended';
                break;
            case -2:
                $status = 'canceled';
                break;
        }

        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect($this->redirectAfterLogoutTo)
        ->with('danger', 'messages.alert.' . $status);
    }
}